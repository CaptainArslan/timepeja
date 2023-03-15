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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizations = Organization::get();
        $vehicle_types = VehicleType::get();
        $vehicles = Vehicle::with('organizations', 'vehiclesTypes')
        ->orderBy('id', 'DESC')
        ->take(10)
        ->get();
        return view('vehicle.index', [
            'organizations' => $organizations,
            'vehicles' => $vehicles,
            'vehicle_types' => $vehicle_types
        ]);
        // $vehicles = Vehicle::with('organizations', 'vehiclesTypes')
        // ->get();
        // return view('vehicle.index', ['vehivles' => $vehicles]);
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

        // dd($request->all());
        if ($request->hasFile('veh_front_pic')) {
            $file = $request->file('veh_front_pic');
            $extension = $file->getClientOriginalExtension();
            $front_filename = time() . '.' . $extension;
            $file->move(public_path('uploads/vehicles'), $front_filename);
        }
        if ($request->hasFile('veh_license_plate')) {
            $file = $request->file('veh_license_plate');
            $extension = $file->getClientOriginalExtension();
            $back_filename = time() . '.' . $extension;
            $file->move(public_path('uploads/vehicles'), $back_filename);
        }

        $user = Auth::user();
        $vehicle = new Vehicle();
        $vehicle->u_id  = $user->id;
        $vehicle->o_id   = $request->o_id;
        $vehicle->v_type_id    = $request->v_type_id;
        $vehicle->number   = $request->number;
        $vehicle->front_pic   = $front_filename;
        $vehicle->number_pic   = $back_filename;
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
}
