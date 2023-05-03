<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MediaController extends BaseController
{
    /**
     * Upload image
     *
     * @param Request $request
     * @return void
     */
    public function uploadMedia(Request $request)
    {
        // return $request->all();
        $validator = Validator::make(
            $request->all(),
            [
                'image' => ['required'],
                'image.*' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
                'type' => ['required', 'string'],
            ],
            [
                'image.required' => 'Please upload an image',
                'image.*.mimes' => 'Only jpeg, png, jpg, gif, and svg formats are allowed',
                'image.*.max' => 'The image may not be greater than 2 MB in size',
                'type.required' => 'Please select an image type',
                'type.string' => 'The image type must be a string',
            ]
        );

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        $allowed_types = ['manager_profile', 'vehicle', 'driver_cnic', 'driver_license', 'driver_profile', 'passenger_profile'];
        if (!in_array($request->type, $allowed_types)) {
            return $this->respondWithError('Invalid type');
        }

        try {
            $name = [];
            foreach ($request->file('image') as $image) {
                switch ($request->type) {
                    case 'manager_profile':
                        $name[] = uploadImage($image, 'managers/profiles/', 'profile');
                        break;
                    case 'vehicle':
                        $name[] = uploadImage($image, 'vehicles/', 'vehicle');
                        break;
                    case 'driver_cnic':
                        $name[] = uploadImage($image, 'drivers/cnic', 'cnic');
                        break;
                    case 'driver_license':
                        $name[] = uploadImage($image, 'drivers/license', 'license');
                        break;
                    case 'driver_profile':
                        $name[] = uploadImage($image, 'drivers/profile', 'profile');
                        break;
                    default:
                        return $this->respondWithError('Invalid image type');
                }
            }

            return $this->respondWithSuccess($name, 'Image uploaded successfully', 'API_IMAGE_UPLOAD_SUCCESS');
        } catch (\Throwable $th) {
            return $this->respondWithError($th->getMessage());
        }
    }
}
