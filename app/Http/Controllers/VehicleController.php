<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    /**
     * roles and permission middleware
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {
        // $this->middleware(function ($request, $next) {
        //     app(UserController::class)->main();
        //     return $next($request);
        // });
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $organizations = Organization::get();
        $vehicle_types = VehicleType::get();
        $vehicles = Vehicle::with(['organizations' => function ($query) {
            $query->select('id', 'name'); // Select the id and name columns from the organizations table
        }])
            ->with(['vehiclesTypes' => function ($query) {
                $query->select('id', 'name'); // Select the id and name columns from the vehicles_types table
            }])
            ->orderBy('id', 'DESC')
            ->take(10)
            ->get(); // Select only the id and name columns from the vehicles table

        // dd($vehicles->toArray());
        if ($request->isMethod('post')) {
            if ($request->has('filter')) {
                $vehicles = $this->filter($request);
            }
        }
        return view('vehicle.index', [
            'organizations' => $organizations,
            'vehicle_types' => $vehicle_types,
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function filter(Request $request)
    {
        $request->validate([
            'o_id' => 'nullable|numeric',
            'from' => 'nullable|date',
            'to' => 'nullable|date|after:from',
        ], [
            'to.after' => "The registration to date must be a date after registration from.",
        ]);
        // Get the input values from the request
        $o_id = $request->input('o_id');
        $from = $request->input('from');
        $to = $request->input('to');

        // Get the filtered records
        $records = Vehicle::when($o_id, function ($query, $o_id) {
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
            ->with('vehiclesTypes', function ($query) {
                $query->select('id', 'name'); // Select the id and name columns from the vehicles_types table
            })
            ->get();

        // Return the filtered records to the view
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
        $vehicle_types = VehicleType::get();
        $vehicles = Vehicle::with('organizations', 'vehiclesTypes')
            ->latest()
            ->take(10)
            ->get();
        // dd($vehicles->toArray());
        return view('vehicle.index', [
            'organizations' => $organizations,
            'vehicle_types' => $vehicle_types,
            'vehivles' => $vehicles
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
            'o_id' => 'required|integer',
            'v_type_id' => 'required|integer',
            'number' => 'required',
            'veh_front_pic' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'veh_license_plate' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'o_id.required' => 'Organization required',
            'v_type_id.required' => 'Vehicle required',
            'number.required' => 'Vehicle number required',
            'veh_front_pic.required' => 'Vehicle Front pic required',
            'veh_front_pic.mimes' => 'The image must be a JPEG or PNG file',
            'veh_license_plate.required' => 'License Vehicle plate required',
            'veh_license_plate.mimes' => 'The image must be a JPEG or PNG file',
        ]);

        if ($request->hasFile('veh_front_pic')) {
            $imageFront = $request->file('veh_front_pic');
        }

        if ($request->hasFile('veh_license_plate')) {
            $imageNumber = $request->file('veh_license_plate');
        }

        $user = Auth::user();
        $vehicle = new Vehicle();
        $vehicle->u_id  = $user->id;
        $vehicle->o_id   = $request->o_id;
        $vehicle->v_type_id    = $request->v_type_id;
        $vehicle->number   = $request->number;
        $vehicle->front_pic   = ($imageFront) ? uploadImage($imageFront, 'vehicles') : null;
        $vehicle->number_pic   = ($imageNumber) ?  uploadImage($imageNumber, 'vehicles') : null;
        if ($vehicle->save()) {
            return redirect()->route('vehicle.index')
                ->with('success', 'Vehicle created successfully.');
        } else {
            return redirect()->route('vehicle.index')
                ->with('error', 'Error Occured while Vehicle creation.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function show(Vehicle $vehicle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer',
            'o_id' => 'required|integer',
            'v_type_id' => 'required|integer',
            'number' => 'required',
            'veh_front_pic' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'veh_license_plate' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'id.required' => 'Id is required to edit',
            'o_id.required' => 'Organization required',
            'v_type_id.required' => 'Vehicle required',
            'number.required' => 'Vehicle number required',
            'veh_front_pic.required' => 'Vehicle Front pic required',
            'veh_front_pic.mimes' => 'The image must be a JPEG or PNG file',
            'veh_license_plate.required' => 'License Vehicle plate required',
            'veh_license_plate.mimes' => 'The image must be a JPEG or PNG file',
        ]);

        $vehicle = Vehicle::find($request->id);
        $user = Auth::user();

        if ($request->hasFile('veh_front_pic')) {
            removeImage($vehicle->front_pic_name, 'vehicles');
            $imageFront = $request->file('veh_front_pic');
        }

        if ($request->hasFile('veh_license_plate')) {
            removeImage($vehicle->number_pic_name, 'vehicles');
            $imageNumber = $request->file('veh_license_plate');
        }

        $imageFront = $request->file('veh_front_pic');
        $imageNumber = $request->file('veh_license_plate');

        $vehicle->u_id  = $user->id;
        $vehicle->o_id   = $request->o_id;
        $vehicle->v_type_id    = $request->v_type_id;
        $vehicle->number   = $request->number;
        $vehicle->front_pic   = ($imageFront) ? uploadImage($imageFront, 'vehicles') : $vehicle->front_pic;
        $vehicle->number_pic   = ($imageNumber) ?  uploadImage($imageNumber, 'vehicles') : $vehicle->number_pic;

        if ($vehicle->save()) {
            return redirect()->route('vehicle.index')
                ->with('success', 'Vehicle updated successfully.');
        } else {
            return redirect()->route('vehicle.index')
                ->with('error', 'Error Occured while Vehicle updation.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Vehicle $vehicle)
    {
        $delete = $vehicle::find($request->id)->delete();
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

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function multiDelete(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                foreach ($request->vehicle_ids as $vehicle_id) {
                    $delete = Vehicle::where('id', $vehicle_id)->delete();
                    if (!$delete) {
                        throw new \Exception('Error updating schedule.');
                    }
                }
            });
            return redirect()->route('vehicle.index')
                ->with('success', 'Vehicles deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('vehicle.index')
                ->with('error', 'Error occured while Vehicle deletion.');
        }
        // $delete = Vehicle::whereIn($request->vehicle_ids)->delete();
        // if ($delete) {
        //     return redirect()->route('vehicle.index')
        //     ->with('success', 'Vehicles deleted successfully.');
        // } else {
        //     return redirect()->route('vehicle.index')
        //     ->with('error', 'Error occured while Vehicle deletion.');
        // }
    }
}
