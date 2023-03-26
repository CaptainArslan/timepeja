<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizations = Organization::get();
        $drivers = Driver::with(['organization' => function ($query) {
            $query->select('id', 'name', 'email');
        }])
            ->latest()
            ->take(10)
            ->orderBy('id', 'DESC')
            ->get();
            // dd($drivers->toArray());
        return view('driver.index', [
            'drivers' => $drivers,
            'organizations' => $organizations,
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
        $drivers = Driver::with('organizations')
            ->latest()
            ->take(10)
            ->orderBy('id', 'DESC')
            ->get();
        // dd($drivers->toArray());
        return view('driver.create', [
            'organizations' => $organizations,
            'drivers' => $drivers
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
        $request->validate([
            'o_id' => 'required|string',
            'name' => 'required|string',
            'phone' => 'required|string|unique:drivers,phone',
            'cnic' => 'required|string|unique:drivers,cnic',
            'license' => 'required|string|unique:drivers,license_no',
            'status' => 'required|numeric',
            'cnic_front' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cnic_back' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'license_front' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'license_back' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'o_id.required' => 'Organization required'
        ]);

        $user = Auth::user();
        $driver = new Driver();
        $driver->o_id = $request->input('o_id');
        $driver->u_id = $user->id;
        $driver->name = $request->input('name');
        $driver->phone = $request->input('phone');
        $driver->cnic = $request->input('cnic');
        $driver->license_no = $request->input('license');
        $driver->otp = substr(uniqid(), -4);
        $driver->status = $request->input('status');

        $driver->cnic_front_pic = ($request->file('cnic_front')) ?
            uploadImage($request->file('cnic_front'), 'drivers/cnic')
            : null;
        $driver->cnic_back_pic =  ($request->file('cnic_back')) ?
            uploadImage($request->file('cnic_back'), 'drivers/cnic') :
            null;

        $driver->license_no_front_pic =  ($request->file('license_front')) ?
            uploadImage($request->file('license_front'), 'drivers/license') :
            null;
        $driver->license_no_back_pic = ($request->file('license_back')) ?
            uploadImage($request->file('license_back'), 'drivers/license') :
            null;

        if ($driver->save()) {
            return redirect()->route('driver.index')
                ->with('success', 'Driver created successfully.');
        } else {
            return redirect()->route('driver.index')
                ->with('error', 'Error Occured while Driver creation .');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(Driver $driver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Driver $driver)
    {
        dd($request->all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Driver $driver)
    {
        dd($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if (Driver::where('id', $id)->delete()) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error']);
        }
    }

    public function multiDelete(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                foreach ($request->driver_ids as $driver_id) {
                    $delete = Driver::where('id', $driver_id)->delete();
                    if (!$delete) {
                        throw new \Exception('Error updating schedule.');
                    }
                }
            });
            return redirect()->route('driver.index')
                ->with('success', 'Vehicles deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('driver.index')
                ->with('error', 'Error occured while Vehicle deletion.');
        }
    }

    public function upcomingTrips(Request $request)
    {
        $organizations = Organization::get();
        return view('driver.trips', [
            'organizations' => $organizations
        ]);
    }
}
