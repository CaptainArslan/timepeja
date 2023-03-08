<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Organization;
use Illuminate\Http\Request;
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
        $drivers = Driver::with('organizations')
            ->get();
        return view('driver.index', ['drivers' => $drivers]);
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
            'org_name' => 'required|string',
            'name' => 'required|string',
            'phone' => 'required|string|unique:drivers,phone',
            'cnic' => 'required|string',
            'license' => 'required|string',
            'status' => 'required|numeric',
            'cnic_front' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cnic_back' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'license_front' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'license_back' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('cnic_front')) {
            $image_front_cnic = $request->file('cnic_front');
            $cnic_front_name = time() . $image_front_cnic->getClientOriginalName();
            $image_front_cnic->move(public_path('/images/drivers/cnic/'), $cnic_front_name);
            $cnic_front_path = "/images/drivers/cnic/" . $cnic_front_name;
        }

        if ($request->hasFile('cnic_back')) {
            $image_back_cnic = $request->file('cnic_back');
            $cnic_back_name = time() . $image_back_cnic->getClientOriginalName();
            $image_back_cnic->move(public_path('/images/drivers/cnic/'), $cnic_back_name);
            $cnic_back_path = "/images/drivers/cnic/" . $cnic_back_name;
        }

        if ($request->hasFile('license_front')) {
            $image_front_license = $request->file('license_front');
            $license_front_name = time() . $image_front_license->getClientOriginalName();
            $image_front_license->move(public_path('/images/drivers/license/'), $license_front_name);
            $license_front_path = "/images/drivers/license/" . $license_front_name;
        }

        if ($request->hasFile('license_back')) {
            $image_back_license = $request->file('license_back');
            $license_back_name = time() . $image_back_license->getClientOriginalName();
            $image_back_license->move(public_path('/images/drivers/license/'), $license_back_name);
            $license_back_path = "/images/drivers/license/" . $license_back_name;
        }

        $user = Auth::user();
        $driver = new Driver();
        $driver->o_id = $request->input('org_name');
        $driver->u_id = $user->id;
        $driver->name = $request->input('name');
        $driver->phone = $request->input('phone');
        $driver->cnic = $request->input('cnic');
        $driver->license_no = $request->input('license');
        $driver->otp = substr(uniqid(), -4);
        $driver->status = $request->input('status');

        $driver->cnic_front_pic =  $cnic_front_path;
        $driver->cnic_back_pic =  $cnic_back_path;

        $driver->license_no_front_pic =  $license_front_path;
        $driver->license_no_back_pic = $license_back_path;

        if ($driver->save()) {
            return redirect()->route('driver.create')
                ->with('success', 'Driver created successfully.');
        } else {
            return redirect()->route('driver.create')
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
    public function edit(Driver $driver)
    {
        //
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
    public function destroy(Driver $driver)
    {

        if ($driver->delete()) {
            return response()->json(['status'=> 'success']);
        } else {
            return response()->json(['status' => 'error']);
        }
    }
}
