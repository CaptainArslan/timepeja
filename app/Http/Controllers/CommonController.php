<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Driver;
use App\Models\Route;
use App\Models\State;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function getStates($country_id)
    {
        $res = State::get();
        if (!empty($country_id)) {
            $res = State::where('ctry_id', $country_id)
                ->select('id', 'name')
                ->get();
        }
        return response()->json([
            'status' => 'success',
            'data' => $res
        ]);
    }

    public function getCities($state_id)
    {
        $res = City::get();
        if (!empty($state_id)) {
            $res = City::where('s_id', $state_id)
                ->select('id', 'name')
                ->get();
        }
        return response()->json([
            'status' => 'success',
            'data' => $res
        ]);
    }

    public function getScheduleRouteDriverVehicle($org_id)
    {
        $routes = Route::where('o_id', $org_id)->select('id', 'name')->get();
        $vehicles = Vehicle::where('o_id', $org_id)->select('id', 'number as  name')->get();
        $drivers = Driver::where('o_id', $org_id)->select('id', 'name')->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'routes' => $routes,
                'vehicles' => $vehicles,
                'drivers' => $drivers,
            ]
        ]);
    }

    public function moveImageGetName(Request $request, $inputName, $path)
    {
        if ($request->hasFile($inputName)) {
            $file = $request->file($inputName);
            $cnic_front_name = time() . $file->getClientOriginalName();
            $file->move(public_path($path), $cnic_front_name);
            $cnic_front_path = $path . $cnic_front_name;

            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('uploads/'), $filename);
            $data['image'] = 'public/uploads/' . $filename;
        }
    }

    public function getDrivers($id)
    {
        $drivers = Driver::where('o_id', $id)->select('id', 'name')->get();
        return response()->json([
            'status' => 'success',
            'data' => $drivers
        ]);
    }
}