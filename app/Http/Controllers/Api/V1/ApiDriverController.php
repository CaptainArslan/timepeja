<?php

namespace App\Http\Controllers\Api\V1;

use PDF;
use App\Models\Driver;
use Illuminate\Http\Request;
use App\Models\Pdf as ModelsPdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ApiDriverController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        try {
            $manager = auth('manager')->user();
            $driver = Driver::where('o_id', $manager->o_id)
                ->where('status', Driver::STATUS_ACTIVE)
                ->paginate(Driver::DRIVER_LIMIT_PER_PAGE);
            return $this->respondWithSuccess($driver, 'Oganization All Driver', 'ORGANIZATION_DRIVER');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while fetching organization driver' . $th->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): JsonResponse
    {
        return $this->sendResponse([], 'Driver create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            // 'o_id' => ['required', 'string'],
            'name' => ['required', 'string'],
            'phone' => ['required', 'string', 'unique:drivers,phone'],
            'cnic' => [
                'required',
                'string',
                'unique:drivers,cnic',
                // 'regex:/^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$/',
            ],
            'license_no' => [
                'required',
                'string',
                'unique:drivers,license_no',
                // 'regex:/^\d{10}-[A-Za-z]{3}+$/',
            ],
            'cnic_front' => [
                'required',
                'string'
                // 'image',
                // 'mimes:jpeg,png,jpg,gif,svg',
                // 'max:2048'
            ],
            'cnic_back' => [
                'required',
                'string'
                // 'image',
                // 'mimes:jpeg,png,jpg,gif,svg',
                // 'max:2048'
            ],
            'license_front' => [
                'required',
                'string'
                // 'image',
                // 'mimes:jpeg,png,jpg,gif,svg',
                // 'max:2048'
            ],
            'license_back' => [
                'required',
                'string'
                // 'image',
                // 'mimes:jpeg,png,jpg,gif,svg',
                // 'max:2048'
            ],
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
            'license_no.regex' => 'License format is invalid, must be 10 digit number and 3 character string eg 1234567890-ABC',
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
        }

        $manager = auth('manager')->user();
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
            'cnic_front_pic'            => $request->cnic_front,
            'cnic_back_pic'             => $request->cnic_back,
            'license_no_front_pic'      => $request->license_front,
            'license_no_back_pic'       => $request->license_back,
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
    public function show($id): JsonResponse
    {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric', 'exists:drivers,id']
        ], [
            'id.exists' => 'Invalid Driver id',
            'id.required' => 'Driver id is required'
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }
        try {
            // $manager = auth('manager')->user();
            $driver = Driver::findOrFail($id);
                // ->where('o_id', $manager->o_id)
                // ->where('status', Driver::STATUS_ACTIVE)
                // ->firstOrFail();

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
    public function edit(Request $request, $id): JsonResponse
    {
        return $request->all();
        return $this->respondWithError('Method not allowed');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): JsonResponse
    {
        // return $request->all();
        try {
            $driver = Driver::findOrFail($id);

            $validator = Validator::make(
                $request->all(),
                [
                    'name' => ['required', 'string'],
                    'phone' => [
                        'required',
                        'string',
                        'unique:drivers,phone,' . $id,
                        // Rule::unique('drivers')->ignore($id)
                    ],
                    'cnic' => [
                        'required',
                        'string',
                        'unique:drivers,cnic,' . $id,
                        // 'regex:/^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$/',
                        // Rule::unique('drivers')->ignore($id),
                    ],
                    'license_no' => [
                        'required',
                        'string',
                        'unique:drivers,license_no,' . $id,
                        // 'regex:/^\d{10}-[A-Za-z]{3}+$/'
                        // Rule::unique('drivers')->ignore($id),
                    ],
                    'cnic_front' => [
                        'required',
                        'string'
                        // 'image',
                        // 'mimes:jpeg,png,jpg,gif,svg',
                        // 'max:2048'
                    ],
                    'cnic_back' => [
                        'required',
                        'string'
                        // 'image',
                        // 'mimes:jpeg,png,jpg,gif,svg',
                        // 'max:2048'
                    ],
                    'license_front' => [
                        'required',
                        'string'
                        // 'image',
                        // 'mimes:jpeg,png,jpg,gif,svg',
                        // 'max:2048'
                    ],
                    'license_back' => [
                        'required',
                        'string'
                        // 'image',
                        // 'mimes:jpeg,png,jpg,gif,svg',
                        // 'max:2048'
                    ],
                ],
                [
                    'o_id.required' => 'Organization required',
                    'name.required' => 'Name required',
                    'phone.required' => 'Phone required',
                    'phone.unique' => 'Phone already exists',
                    'cnic.required' => 'CNIC required',
                    'cnic.unique' => 'CNIC already exists',
                    'cnic.regex' => 'CNIC format is invalid',
                    'license_no.required' => 'License required',
                    'license_no.unique' => 'License already exists',
                    'license_no.regex' => 'License format is invalid, must be 10 digit number and 3 character string eg 1234567890-ABC',
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
                ]
            );

            if ($validator->fails()) {
                return $this->respondWithError($validator->errors()->first());
            }
            // update values
            $driver->name = $request->name;
            $driver->phone = $request->phone;
            $driver->cnic = $request->cnic;
            $driver->license_no = $request->license_no;

            // ----- these function are to remove old picture from the folder
            if ($request->has('cnic_front') && $driver->cnic_front_pic_name != null) {
                removeImage($driver->cnic_front_pic_name, 'drivers/cnic');
            }

            if ($request->has('cnic_back') && $driver->cnic_back_pic_name != null) {
                removeImage($driver->cnic_back_pic_name, 'drivers/cnic');
            }

            if ($request->has('license_front') && $driver->license_no_front_pic_name != null) {
                removeImage($driver->license_no_front_pic_name, 'drivers/license');
            }

            if ($request->has('license_back') && $driver->license_no_back_pic_name != null) {
                removeImage($driver->license_no_back_pic_name, 'drivers/license');
            }

            $driver->cnic_front_pic = ($request->has('cnic_front')) ?
                $request->cnic_front
                : $driver->cnic_front_pic_name;
            $driver->cnic_back_pic =  ($request->has('cnic_back')) ?
                $request->cnic_back :
                $driver->cnic_back_pic_name;

            $driver->license_no_front_pic =  ($request->has('license_front')) ?
                $request->license_front :
                $driver->license_no_front_pic_name;
            $driver->license_no_back_pic = ($request->has('license_back')) ?
                $request->license_back :
                $driver->license_no_back_pic_name;
            $save = $driver->save();

            if (!$save) {
                return $this->respondWithError('Error Occured while updating');
            }

            // if ($driver->save()) {
            //     return redirect()->route('driver.index')
            //         ->with('success', 'Driver updated successfully.');
            // } else {
            //     return redirect()->route('driver.index')
            //         ->with('error', 'Error occured while driver updation .');
            // }
            return $this->respondWithSuccess($driver, 'Driver updated successfully', 'API_DRIVER_UPDATED');
        } catch (ModelNotFoundException $e) {
            return $this->respondWithError('Driver id not found');
            // throw new NotFoundHttpException('Driver id not found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): JsonResponse
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
            return $this->respondWithDelete('Driver deleted successfully', 'API_DRIVER_DELETED');
        } catch (ModelNotFoundException $e) {
            return $this->respondWithError('Driver id not found');
            // throw new NotFoundHttpException('Driver id not found');
        }
    }

    /**
     * Search the specified resource from storage.
     *
     * @param  string  $string
     * @return \Illuminate\Http\Response
     */
    public function search(): JsonResponse
    {
        try {
            $string = request()->input('string');
            $manager = auth('manager')->user();
            $drivers = Driver::where('name', 'like', '%' . $string . '%')
                ->orWhere('phone', 'like', '%' . $string . '%')
                ->orWhere('cnic', 'like', '%' . $string . '%')
                ->orWhere('license_no', 'like', '%' . $string . '%')
                ->where('o_id', $manager->o_id)
                ->select('id', 'name')
                ->get();
            // if ($drivers->isEmpty()) {
            //     return $this->respondWithError('No data found');
            // }
            return $this->respondWithSuccess($drivers, 'Drivers retrieved successfully', 'API_DRIVER_SEARCH_RESULT');
        } catch (ModelNotFoundException $e) {
            throw new NotFoundHttpException('Error occured while fetching data');
        }
    }

    /**
     * get driver wihtout pagination for manager web
     *
     * @return JsonResponse
     */
    public function getDriver(): JsonResponse
    {
        try {
            $manager = auth('manager')->user();
            $driver = Driver::where('o_id', $manager->o_id)
                ->where('status', Driver::STATUS_ACTIVE)
                ->get();
            return $this->respondWithSuccess($driver, 'Oganization All Driver', 'ORGANIZATION_DRIVER');
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while fetching organization driver' . $th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeWeb(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            // 'o_id' => ['required', 'string'],
            'name' => ['required', 'string'],
            'phone' => ['required', 'string', 'unique:drivers,phone'],
            'cnic' => [
                'required',
                'string',
                'unique:drivers,cnic',
                // 'regex:/^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$/',
            ],
            'license_no' => [
                'required',
                'string',
                'unique:drivers,license_no',
                // 'regex:/^\d{10}-[A-Za-z]{3}+$/',
            ],
            'cnic_front' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048'
            ],
            'cnic_back' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048'
            ],
            'license_front' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048'
            ],
            'license_back' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048'
            ],
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
            'license_no.regex' => 'License format is invalid, must be 10 digit number and 3 character string eg 1234567890-ABC',
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
            return $this->respondWithError(implode(",", $validator->errors()->all()));
        }

        $manager = auth('manager')->user();
        $otp = rand(1000, 9999);
        $data = [
            'o_id'                      => $manager->o_id,
            'u_id'                      => $manager->id,
            'name'                      => $request->name,
            'phone'                     => $request->phone,
            'cnic'                      => $request->cnic,
            'license_no'                => $request->license_no,
            'otp'                       => $otp,
            'cnic_front_pic'            => ($request->file('cnic_front')) ?
                uploadImage($request->file('cnic_front'), 'drivers/cnic', 'driver_cnic')
                : null,
            'cnic_back_pic'             => ($request->file('cnic_back')) ?
                uploadImage($request->file('cnic_back'), 'drivers/cnic', 'driver_cnic') :
                null,
            'license_no_front_pic'      => ($request->file('license_front')) ?
                uploadImage($request->file('license_front'), 'drivers/license', 'driver_license') :
                null,
            // $request->license_front,
            'license_no_back_pic'       => ($request->file('license_back')) ?
                uploadImage($request->file('license_back'), 'drivers/license', 'driver_license') :
                null,
            // $request->license_back,
        ];
        $save = Driver::create($data);
        if (!$save) {
            return $this->respondWithError('Error Occured while creating Driver');
        }
        $data = Driver::where('id', $save->id)->get();
        return $this->respondWithSuccess($data, 'Driver Creadted Successfully', 'DRIVER_CREATED');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateWeb(Request $request, $id): JsonResponse
    {
        // return $request->all();
        try {
            $driver = Driver::findOrFail($id);

            $validator = Validator::make(
                $request->all(),
                [
                    'name' => ['required', 'string'],
                    'phone' => [
                        'required',
                        'string',
                        'unique:drivers,phone,' . $id,
                        // Rule::unique('drivers')->ignore($id)
                    ],
                    'cnic' => [
                        'required',
                        'string',
                        'unique:drivers,cnic,' . $id,
                        // 'regex:/^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$/',
                        // Rule::unique('drivers')->ignore($id),
                    ],
                    'license_no' => [
                        'required',
                        'string',
                        'unique:drivers,license_no,' . $id,
                        // 'regex:/^\d{10}-[A-Za-z]{3}+$/'
                        // Rule::unique('drivers')->ignore($id),
                    ],
                    'cnic_front' => [
                        // 'required',
                        'image',
                        'mimes:jpeg,png,jpg,gif,svg',
                        'max:2048'
                    ],
                    'cnic_back' => [
                        // 'required',
                        'image',
                        'mimes:jpeg,png,jpg,gif,svg',
                        'max:2048'
                    ],
                    'license_front' => [
                        // 'required',
                        'image',
                        'mimes:jpeg,png,jpg,gif,svg',
                        'max:2048'
                    ],
                    'license_back' => [
                        // 'required',
                        'image',
                        'mimes:jpeg,png,jpg,gif,svg',
                        'max:2048'
                    ],
                ],
                [
                    'o_id.required' => 'Organization required',
                    'name.required' => 'Name required',
                    'phone.required' => 'Phone required',
                    'phone.unique' => 'Phone already exists',
                    'cnic.required' => 'CNIC required',
                    'cnic.unique' => 'CNIC already exists',
                    'cnic.regex' => 'CNIC format is invalid',
                    'license_no.required' => 'License required',
                    'license_no.unique' => 'License already exists',
                    'license_no.regex' => 'License format is invalid, must be 10 digit number and 3 character string eg 1234567890-ABC',
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
                ]
            );

            if ($validator->fails()) {
                return $this->respondWithError($validator->errors()->first());
            }
            // update values
            $driver->name = $request->name;
            $driver->phone = $request->phone;
            $driver->cnic = $request->cnic;
            $driver->license_no = $request->license_no;

            // ----- these function are to remove old picture from the folder
            if ($request->has('cnic_front') && $driver->cnic_front_pic_name != null) {
                removeImage($driver->cnic_front_pic_name, 'drivers/cnic');
            }

            if ($request->has('cnic_back') && $driver->cnic_back_pic_name != null) {
                removeImage($driver->cnic_back_pic_name, 'drivers/cnic');
            }

            if ($request->has('license_front') && $driver->license_no_front_pic_name != null) {
                removeImage($driver->license_no_front_pic_name, 'drivers/license');
            }

            if ($request->has('license_back') && $driver->license_no_back_pic_name != null) {
                removeImage($driver->license_no_back_pic_name, 'drivers/license');
            }

            $driver->cnic_front_pic =
                ($request->file('cnic_front')) ?
                uploadImage($request->file('cnic_front'), 'drivers/cnic', 'driver_cnic')
                : $driver->cnic_front_pic_name;
            $driver->cnic_back_pic =  ($request->file('cnic_back')) ?
                uploadImage($request->file('cnic_back'), 'drivers/cnic', 'driver_cnic') :
                $driver->cnic_back_pic_name;

            $driver->license_no_front_pic =  ($request->file('license_front')) ?
                uploadImage($request->file('license_front'), 'drivers/license', 'driver_license') :
                $driver->license_no_front_pic_name;
            $driver->license_no_back_pic = ($request->file('license_back')) ?
                uploadImage($request->file('license_back'), 'drivers/license', 'driver_license') :
                $driver->license_no_back_pic_name;

            $save = $driver->save();

            if (!$save) {
                return $this->respondWithError('Error Occured while updating');
            }
            return $this->respondWithSuccess($driver, 'Driver updated successfully', 'API_DRIVER_UPDATED');
        } catch (ModelNotFoundException $e) {
            return $this->respondWithError('Driver not found');
            // throw new NotFoundHttpException('Driver id not found');
        }
    }

    /**
     * Create Pdf for drivers
     *
     * @param Request $request
     * @return void
     */
    public function createPdf(Request $request)
    {
        try {
            $manager = auth('manager')->user();
            $drivers = Driver::where('o_id', $manager->o_id)
                ->with('organization:id,name,branch_name,branch_code,email,phone,address,code')
                ->get();
            $data = [
                'drivers' => $drivers->toArray(),
                'request' => $request->all()
            ];
            $pdf = PDF::loadview('pdf.driver', $data);
            $pdf->setPaper('A4', 'landscape');

            $filename = date('Ymd_His') . '_Driver_Report.pdf'; // Generate a unique filename
            $filePath = public_path('uploads/pdf/' . $filename); // Get the full file path

            $pdf->save($filePath); // Save the PDF to the specified folder

            $pdfModel = new ModelsPdf();
            $pdfModel->url = asset('/uploads/pdf/' . $filename);

            if ($pdfModel->save()) {
                return $this->respondWithSuccess($pdfModel, 'Pdf Created Successfully', 'LOG_REPORT_PDF_CREATED_SUCCESSFULLY');
            } else {
                // Delete the saved PDF file if model saving failed
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                return $this->respondWithError('Error occurred while creating the PDF. Failed to save the model.');
            }
        } catch (\Throwable $th) {
            // Delete the saved PDF file if an exception occurred
            // if (file_exists($filePath)) {
            //     unlink($filePath);
            // }
            return $this->respondWithError('Error occurred while creating the PDF: ' . $th->getMessage());
        }

    }
}
