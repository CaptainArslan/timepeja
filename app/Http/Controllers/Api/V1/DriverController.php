<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\Driver\DriverStoreRequest;
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
            // 'o_id' => ['required', 'string'],
            'name' => ['required', 'string'],
            'phone' => ['required', 'string', 'unique:drivers,phone'],
            'cnic' => ['required', 'string', 'regex:/^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$/', 'unique:drivers,cnic'],
            'license_no' => [
                'required',
                'string',
                // 'regex:/^\d{10}-[A-Za-z]+$/',
                'unique:drivers,license_no'
            ],
            'cnic_front' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'cnic_back' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'license_front' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'license_back' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ], [
            'o_id.required' => 'Organization required',
            'name.required' => 'Name required',
            'phone.required' => 'Phone required',
            'phone.unique' => 'Phone already exists',
            'cnic.required' => 'CNIC required',
            'cnic.unique' => 'CNIC already exists',
            'cnic.regex' => 'CNIC format is invalid',
            'license_no.required' => 'License required',
            'license_no.unique' => 'License already exists',
            'license_no.regex' => 'License format is invalid',
            'cnic_front.required' => 'CNIC front required',
            'cnic_front.image' => 'CNIC front must be an image',
            'cnic_front.mimes' => 'CNIC front must be a file of type: jpeg, png, jpg, gif, svg',
            'cnic_front.max' => 'CNIC front may not be greater than 2048 kilobytes',
            'cnic_back.required' => 'CNIC back required',
            'cnic_back.image' => 'CNIC back must be an image',
            'cnic_back.mimes' => 'CNIC back must be a file of type: jpeg, png, jpg, gif, svg',
            'cnic_back.max' => 'CNIC back may not be greater than 2048 kilobytes',
            'license_front.required' => 'License front required',
            'license_front.image' => 'License front must be an image',
            'license_front.mimes' => 'License front must be a file of type: jpeg, png, jpg, gif, svg',
            'license_front.max' => 'License front may not be greater than 2048 kilobytes',
            'license_back.required' => 'License back required',
            'license_back.image' => 'License back must be an image',
            'license_back.mimes' => 'License back must be a file of type: jpeg, png, jpg, gif, svg',
            'license_back.max' => 'License back may not be greater than 2048 kilobytes',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
            // return $this->respondWithSuccess($validator->errors()->all(), 'message', 'Validation Error');
        }

        $manager = auth('manager')->user();
        // return $this->respondWithError($manager);
        $otp = rand(1000, 9999);
        $data = [
            'o_id'                      => $manager->o_id,
            'u_id'                      => $manager->id,
            'name'                      => $request->name,
            'phone'                     => $request->phone,
            'cnic'                      => $request->cnic,
            'license_no'                => $request->license_no,
            'otp'                       => $otp,
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
            // 'status'                    => ($request->status)
            //     ? Driver::STATUS_ACTIVE
            //     : Driver::STATUS_INACTIVE,
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
