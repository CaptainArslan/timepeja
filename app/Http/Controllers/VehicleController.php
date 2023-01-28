<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizations = Organization::all();
        $vehicle_types = VehicleType::all();
        return view('vehicle.index', compact('organizations', 'vehicle_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'org_id' => 'required|integer',
            'veh_id' => 'required|integer',
            'veh_no' => 'required',
            'veh_front_pic' => '',
            'veh_back_pic' => '',
        ]);

        if ($request->hasFile('veh_front_pic')) {
            $file = $request->file('veh_front_pic');
            $extension = $file->getClientOriginalExtension();
            $front_filename = time() . '.' . $extension;
            $file->move(public_path('uploads/vehicles'), $front_filename);
        }
        if ($request->hasFile('veh_back_pic')) {
            $file = $request->file('veh_back_pic');
            $extension = $file->getClientOriginalExtension();
            $back_filename = time() . '.' . $extension;
            $file->move(public_path('uploads/vehicles'), $back_filename);
        }

        $user = Auth::user();
        $vehicle = new Vehicle();
        $vehicle->user_id  = $user->id;
        $vehicle->org_id   = $request->org_id;
        $vehicle->veh_id   = $request->veh_id;
        $vehicle->veh_num   = $request->veh_no;
        $vehicle->veh_front_pic   = $front_filename;
        $vehicle->veh_back_pic   = $back_filename;
        if ($vehicle->save()) {
            return redirect()->route('vehicles.index')
                ->with('success', 'Vehicle created successfully.');
        } else {
            return redirect()->route('vehicles.index')
                ->with('error', 'Error Occured while Vehicle creation .');
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
    public function edit(Vehicle $vehicle)
    {
        //
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
    public function destroy(Vehicle $vehicle)
    {
        //
    }
}
