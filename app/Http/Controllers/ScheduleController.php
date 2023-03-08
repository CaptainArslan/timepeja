<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizations = Organization::get();
        return view('manager.schedule.create', [
            'organizations' => $organizations
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organizations = Organization::get();
        return view('manager.schedule.create', [
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
        // dd($request->all());
        
        // $id = Schedule::insertGetId([
        //     [
        //         'o_id' => $request->organization,
        //         'u_id' => $user->id,
        //         'route_id' => isset($request->route_no) ? $request->route_no : 1,
        //         'v_id' => isset($request->vehicle) ? $request->vehicle : 1,
        //         'd_id' => isset($request->driver) ? $request->driver : 1,
        //         'date' => $request->date,
        //         'time' => $request->time,
        //         'status' => 0
        //     ]
        // ]);
        $user = Auth::user();
        $id = Schedule::insertGetId([
            'o_id' => $request->organization,
            'u_id' => $user->id,
            'route_id' => isset($request->route_no) ? $request->route_no : 1,
            'v_id' => isset($request->vehicle) ? $request->vehicle : 1,
            'd_id' => isset($request->driver) ? $request->driver : 1,
            'date' => $request->date,
            'time' => $request->time,
            'status' => 0
        ]);
        
        $data = Schedule::where('id', $id)->with('organizations', 'routes', 'vehicles', 'drivers')->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
