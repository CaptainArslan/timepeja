<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LocationController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $locations = Location::with(['vehicle' => function ($query) {
                $query->select('id', 'number', 'v_type_id', 'front_pic', 'number_pic', 'status');
            }, 'vehicle.vehiclesTypes:id,name'])
            ->with('driver:id,name,phone,email')
            ->with('passenger:id,name,phone,email')
            ->with('organization:id,name,phone,email')
            ->select('id', 'organization_id', 'name', 'passenger_id', 'vehicle_id', 'driver_id', 'type', 'latitude', 'longitude')
            ->get();

            return $this->respondWithSuccess($locations, 'location fetched successfully', 'LOCATION_FETCHED');
        } catch (\Throwable $th) {
            return $this->respondWithError($th->getMessage());
        }
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $locations = Location::where('vehicle_id', $id)->where('type', 'driver')->latest()->first();
            return $this->respondWithSuccess($locations, 'Latest location fetched successfully', 'LATEST_LOCATION_FETCHED');
        } catch (\Throwable $th) {
            return $this->respondWithError($th->getMessage());
        }
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
