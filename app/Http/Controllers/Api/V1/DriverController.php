<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DriverController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $manager = auth('manager')->user();
            $driver = Driver::where('o_id', $manager->o_id)->get();
            return $this->respondWithSuccess($driver, 'Oganization All Driver', 'ORGANIZATION_DRIVER');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while fetching organization driver');
            throw $th;
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
        $validator = Validator::make($request->all(), [
            'o_id' => ['required', 'string'],
            'name' => ['required', 'string'],
            'phone' => ['required', 'string', 'unique:drivers,phone'],
            'cnic' => ['required', 'string', 'regex:/^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$/', 'unique:drivers,cnic'],
            'license_no' => ['required', 'string', 'unique:drivers,license_no'],
            'cnic_front' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'cnic_back' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'license_front' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'license_back' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'status' => ['required', 'numeric'],
        ], [
            'o_id.required' => 'Organization required',
            'cnic.regex' => 'Invalid cnic format'
        ]);

        if ($validator->fails()) {
            return $this->respondWithError("Please fill the form correctly");
            // return $this->respondWithSuccess($validator->errors()->all(), 'message', 'Validation Error');
        }
        $manager = auth('manager')->user();
        $data = [
            'o_id'                      => $manager->o_id,
            'u_id'                      => $manager->id,
            'name'                      => $request->name,
            'phone'                     => $request->phone,
            'cnic'                      => $request->cnic,
            'license_no'                => $request->license_no,
            'profile_picture'           => null,
            'address'                   => null,
            'cnic_front_pic'            => ($request->file('cnic_front'))
                ? uploadImage($request->file('cnic_front'), 'drivers/cnic')
                : null,
            'cnic_back_pic'             => ($request->file('cnic_back')) ?
                uploadImage($request->file('cnic_back'), 'drivers/cnic') :
                null,
            'license_no_front_pic'      => ($request->file('license_front')) ?
                uploadImage($request->file('license_front'), 'drivers/license') :
                null,
            'license_no_back_pic'       => ($request->file('license_back')) ?
                uploadImage($request->file('license_back'), 'drivers/license') :
                null,
            'status'                    => ($request->status)
                ? Driver::STATUS_ACTIVE
                : Driver::STATUS_INACTIVE,
        ];
        $save = Driver::create($data);
        if (!$save) {
            return $this->respondWithError('Error Occured while creating Driver');
        }
        $data = Driver::where('id', $save->id)->get();
        return $this->respondWithSuccess($data, 'Driver Creadted Successfully', 'DRIVER_CREATED');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => ['exists:drivers,id', 'required']
        ], [
            'id.exists' => 'Invalid Driver id',
            'id.required' => 'Driver id is required'
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }
        try {
            $manager = auth('manager')->user();
            $driver = Driver::where('id', $id)
                // ->where('o_id', $manager->o_id)
                ->where('status', Driver::STATUS_ACTIVE)
                ->get();

            return $this->respondWithSuccess($driver, 'Get Driver', 'API_GET_DRIVER');
        } catch (ModelNotFoundException $e) {
            return $this->respondWithError('Driver id not found');
            throw new NotFoundHttpException('Driver id not found');
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
        $validator = Validator::make(['id' => $id], [
            'id' => ['exists:drivers,id', 'required']
        ], [
            'id.exists' => 'Invalid driver id',
            'id.required' => 'Driver id is required'
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        try {
            $driver = Driver::findOrFail($id);
            $driver->delete();
            return $this->respondWithSuccess($driver, 'Driver deleted successfully', 'API_DRIVER_DELETED');
        } catch (ModelNotFoundException $e) {
            return $this->respondWithError('Driver id not found');
            throw new NotFoundHttpException('Driver id not found');
        }
    }
}
