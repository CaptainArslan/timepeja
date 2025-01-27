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
    public function profileUpdateWeb(Request $request)
    {
        $manager = auth('manager')->user();
        $validator = Validator::make(
            $request->all(),
            [
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:255', 'unique:managers,phone,' . $manager->id],
                'address' => ['required', 'string', 'max:255'],
                // 'picture' => [
                //     'required',
                //     'image',
                //     'mimes:jpeg,png,jpg,gif',
                //     'max:2048'
                // ],
            ],
            [
                'name.required' => 'Full name is required',
                'name.string' => 'Name must be in string',

                'phone.required' => 'Phone is required',
                'phone.string' => 'phone must be in string',

                'address.required' => 'Address is required',
                'address.string' => 'address must be in string',

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

            // if ($request->has('picture') && $manager->picture_name != null) {
            //     removeImage($manager->picture_name, '/managers/profiles/');
            // }

            // $manager->picture = $request->hasFile('picture')  ? uploadImage($request->file('picture'), '/managers/profiles', 'manager_profile') : $manager->picture_name;

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
                ->select('id', 'number')
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
