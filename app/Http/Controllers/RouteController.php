<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RouteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $organizations = Organization::get();
        $routes = Route::with('organizations')->orderBY('id', 'desc')
            ->take(10)
            ->get();

        if ($request->isMethod('post')) {
            if ($request->has('filter')) {
                $routes = $this->filter($request);
            }
        }
        return view('route.index', [
            'routes' => $routes,
            'organizations' => $organizations
        ]);
    }

    /**
     * Filter the records.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $request->validate([
            'o_id' => ['nullable', 'numeric', 'exists:organizations,id'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after:from'],
        ], [
            'o_id.exists' => "The selected organization doesn't exist.",
            'from.date' => "The registration from date must be a valid date.",
            'to.date' => "The registration to date must be a valid date.",
            'to.after' => "The registration to date must be a date after registration from.",
        ]);
        // Get the input values from the request
        $o_id = $request->input('o_id');
        $from = $request->input('from');
        $to = $request->input('to');

        // Get the filtered records
        $records = Route::when($o_id, function ($query, $o_id) {
            return $query->where('o_id', $o_id);
        })
            ->when($from && $to, function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', [$from, $to]);
            })
            ->when($from && !$to, function ($query) use ($from) {
                return $query->whereDate('created_at', '>=', $from);
            })
            ->when($to && !$from, function ($query) use ($to) {
                return $query->whereDate('created_at', '<=', $to);
            })
            ->with('organizations', function ($query) {
                $query->select('id', 'name'); // Select the id and name columns from the organizations table
            })
            ->groupBy('number')
            ->get();

        // Return the filtered records
        return $records;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organizations = Organization::get();
        $routes = Route::with('organizations')
            ->latest()
            ->take(10)
            ->get();
        return view('route.create', [
            'routes' => $routes,
            'organizations' => $organizations
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'org_id' => ['required', 'int', 'exists:organizations,id'],
            'route_no' => ['required', 'int'],
            'to' => ['required', 'string'],
            'from' => ['required', 'string'],
            'route_name' => ['required', 'string'],
        ], [
            'org_id.required' => 'Organization is required',
            'org_id.int' => 'Organization is required',
            'org_id.exists' => 'Organization is required',
            'route_no.required' => 'Route number is required',
            'route_no.int' => 'Route number is required',
            'to.required' => 'To location is required',
            'to.string' => 'To location is required',
            'from.required' => 'From location is required',
            'from.string' => 'From location is required',
        ]);

        try {
            $user = Auth::user();
            DB::transaction(function () use ($user, $request) {
                $route1 = new Route();
                $route1->u_id = $user->id;
                $route1->o_id = $request->input('org_id');
                $route1->number = $request->input('route_no');
                $route1->to = $request->input('to');
                $route1->from = $request->input('from');
                $route1->name = $request->input('route_no') . ' - ' . $request->input('from') . ' To ' . $request->input('to');
                $first_route_saved = $route1->save();

                $route2 = new Route();
                $route2->u_id = $user->id;
                $route2->o_id = $request->input('org_id');
                $route2->number = $request->input('route_no');
                $route2->to = $request->input('from');
                $route2->from = $request->input('to');
                $route2->name = $request->input('route_no') . ' - ' . $request->input('to') . ' To ' . $request->input('from');
                $second_route_saved = $route2->save();

                if ($first_route_saved && $second_route_saved) {
                    return redirect()->route('routes.index')->with('success', 'Route created successfully.');
                } else {
                    throw new \Exception('Error Occured while route creation.');
                }
            });
        } catch (\Throwable $th) {
            throw new \Exception('Error Occured while route creation.' . $th->getMessage());
        }

        return redirect()->route('routes.index')->with('error', 'Error Occured while route creation.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function show(Route $route)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Route $route)
    {
        $this->validate($request, [
            'org_id' => ['required', 'int', 'exists:organizations,id'],
            'route_no' => ['required', 'int'],
            'to' => ['required', 'string'],
            'from' => ['required', 'string'],
            'route_name' => ['required', 'string'],
        ], [
            'org_id.required' => 'Organization is required',
            'org_id.int' => 'Organization is required',
            'org_id.exists' => 'Organization is required',
            'route_no.required' => 'Route number is required',
            'route_no.int' => 'Route number is required',
            'to.required' => 'To location is required',
            'to.string' => 'To location is required',
            'from.required' => 'From location is required',
            'from.string' => 'From location is required',
        ]);

        // dd($request->all());
        $user = Auth::user();
        $route =  Route::findorFail($request->edit_id);

        $route->u_id = $user->id;
        $route->o_id = $request->input('org_id');
        $route->number  = $request->input('route_no');
        $route->to = $request->input('to');
        $route->from = $request->input('from');
        $route->name = $request->input('route_no') . ' - ' . $request->input('from') . ' To ' . $request->input('to');
        $save = $route->save();

        if ($save) {
            return redirect(route('routes.index'))->with('success', 'Route updated successfully');
        } else {
            return redirect(route('routes.index'))->with('error', 'Error Occured while updating route!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Route $route)
    {
        dd('update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Route $route)
    {
        $delete = $route::find($request->id)->delete();
        if ($delete) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
}
