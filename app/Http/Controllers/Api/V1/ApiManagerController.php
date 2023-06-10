<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Route;
use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiManagerController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        return $this->sendResponse([], 'Manager index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): JsonResponse
    {
        return $this->sendResponse([], 'Manager create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        return $this->sendResponse([], 'Manager store');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): JsonResponse
    {
        return $this->sendResponse([], 'Manager show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): JsonResponse
    {
        return $this->sendResponse([], 'Manager edit');
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
        return $this->sendResponse([], 'Manager update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): jsonResponse
    {
        return $this->sendResponse([], 'Manager destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profileUpload(Request $request): jsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'profile_picture' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ],
            [
                'profile_picture.required' => 'Profile Picture is required',
                'profile_picture.image' => 'Profile Picture must be an image',
                'profile_picture.mimes' => 'Profile Picture must be a file of type: jpeg, png, jpg, gif',
                'profile_picture.max' => 'Profile Picture may not be greater than 2048 kilobytes',
            ]
        );

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        try {
            $manager = auth('manager')->user();

            if ($request->hasFile('profile_picture') && $manager->picture_name != null) {
                removeImage($manager->picture_name, '/managers/profiles/');
            }
            $image = uploadImage($request->file('profile_picture'), '/managers/profiles/', 'profile');

            $manager->picture = $image;
            $data = $manager->select('id', 'picture')->first();
            if ($manager->save()) {
                return $this->respondWithSuccess($data, 'Profile Updated', 'PROFILE_UPDATED');
            } else {
                return $this->respondWithError('Profile not Updated');
            }
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while profile Updated');
        }

        // return $this->respondWithSuccess(null, 'Profile Uploaded', 'PROFILE_UPLOADED');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profileUpdate(Request $request)
    {
        $manager = auth('manager')->user();
        $validator = Validator::make(
            $request->all(),
            [
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:255', 'unique:managers,phone,' . $manager->id],
                'address' => ['required', 'string', 'max:255'],
                'picture' => [
                    'string',
                    // 'required',
                    // 'image',
                    // 'mimes:jpeg,png,jpg,gif',
                    // 'max:2048'
                ],
            ],
            [
                'name.required' => 'Full name is required',
                'name.string' => 'Name must be in string',

                'phone.required' => 'Phone is required',
                'phone.string' => 'phone must be in string',

                'address.required' => 'Address is required',
                'address.string' => 'address must be in string',

                'picture.required' => 'Profile Picture is required',
                'picture.string' => 'address must be in string',
                // 'picture.image' => 'Profile Picture must be an image',
                // 'picture.mimes' => 'Profile Picture must be a file of type: jpeg, png, jpg, gif',
                // 'picture.max' => 'Profile Picture may not be greater than 2048 kilobytes',
            ]
        );

        if ($validator->fails()) {
            return $this->respondWithError(implode(",", $validator->errors()->all()));
        }

        try {
            $manager->name = $request->name;
            $manager->phone = $request->phone;
            $manager->address = $request->address;

            if ($request->has('picture') && $manager->picture_name != null) {
                removeImage($manager->picture_name, '/managers/profiles/');
            }

            $manager->picture = $request->picture ? $request->picture : $manager->picture_name;

            if ($manager->save()) {
                // $data = $manager->select('id', 'picture')->first();
                return $this->respondWithSuccess($manager, 'Profile Updated', 'PROFILE_UPDATED');
            } else {
                return $this->respondWithError('Error Occured while profile Updated');
            }
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while profile Updated');
        }

        // return $this->respondWithSuccess(null, 'Profile Uploaded', 'PROFILE_UPLOADED');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profileUpdateWeb(Request $request)
    {
        $manager = auth('manager')->user();
        $validator = Validator::make(
            $request->all(),
            [
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:255', 'unique:managers,phone,' . $manager->id],
                'address' => ['required', 'string', 'max:255'],
                'picture' => [
                    // 'required',
                    'image',
                    'mimes:jpeg,png,jpg,gif',
                    'max:2048'
                ],
            ],
            [
                'name.required' => 'Full name is required',
                'name.string' => 'Name must be in string',

                'phone.required' => 'Phone is required',
                'phone.string' => 'phone must be in string',

                'address.required' => 'Address is required',
                'address.string' => 'address must be in string',

                'picture.image' => 'Profile Picture must be an image',
                'picture.mimes' => 'Profile Picture must be a file of type: jpeg, png, jpg, gif',
                'picture.max' => 'Profile Picture may not be greater than 2048 kilobytes',
            ]
        );

        if ($validator->fails()) {
            return $this->respondWithError(implode(",", $validator->errors()->all()));
        }

        try {
            $manager->name = $request->name;
            $manager->phone = $request->phone;
            $manager->address = $request->address;

            if ($request->has('picture') && $manager->picture_name != null) {
                removeImage($manager->picture_name, '/managers/profiles/');
            }

            $manager->picture = $request->hasFile('picture')  ? uploadImage($request->file('picture'), '/managers/profiles', 'manager_profile') : $manager->picture_name;

            if ($manager->save()) {
                // $data = $manager->select('id', 'picture')->first();
                return $this->respondWithSuccess($manager, 'Profile Updated', 'PROFILE_UPDATED');
            } else {
                return $this->respondWithError('Error Occured while profile Updated');
            }
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while profile Updated');
        }

        // return $this->respondWithSuccess(null, 'Profile Uploaded', 'PROFILE_UPLOADED');
    }

    /**
     * main screen wrapper
     *
     * @param Request $request
     * @return void
     */
    public function wrapper(): jsonResponse
    {
        try {
            $manager = auth('manager')->user();
            $data = [];
            $routes = Route::where('o_id', $manager->o_id)
                ->where('status', Route::STATUS_ACTIVE)
                ->select('id', 'name')
                ->get();

            $vehicles = Vehicle::where('o_id', $manager->o_id)
                ->where('status', Vehicle::STATUS_ACTIVE)
                ->select('id', 'number as  name')
                ->get();

            $drivers = Driver::where('o_id', $manager->o_id)
                ->where('status', Driver::STATUS_ACTIVE)
                ->select('id', 'name')
                ->get();

            $data = [
                'routes' => $routes,
                'vehicles' => $vehicles,
                'drivers' => $drivers,
            ];

            // store data in cache
            Cache::put('SCREEN_WRAPPER_' . $manager->o_id, $data, now()->addDay(1));
        } catch (ModelNotFoundException $e) {
            throw new NotFoundHttpException('Data not found ' . $e->getMessage());
        }

        return $this->respondWithSuccess(
            $data,
            'Organization route, vehicle, driver data',
            'ORGANIZATION_ROUTE_VEHICLE_DRIVER_DATA'
        );
    }
}
