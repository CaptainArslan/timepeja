<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\OtherMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                'type' => ['required', 'string'],
                'image.*' => ['required', 'max:2048'],
            ],
            [
                'image.required' => 'Please upload an image',
                'image.*.mimes' => 'Only jpeg, png, jpg, gif, and svg formats are allowed',
                'image.*.max' => 'The image may not be greater than 2 MB in size',
                'type.required' => 'Please select type',
                'type.string' => 'The type must be a string',
            ]
        );

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->first());
        }

        $images = $request->file('image');
        if (!$images) {
            return $this->respondWithError('No image files found');
        }

        $types = [
            'vehicle' => 'vehicles/',
            'driver_cnic' => 'drivers/cnic/',
            'driver_profile' => 'drivers/profile/',
            'driver_license' => 'drivers/license/',
            'manager_profile' => 'managers/profiles/',
            'passenger' => 'passengers/',
            'guardian_cnic' => 'guardian/cnic/',
            'profile_card' => 'passengers/profile/',
        ];
        $uploaded = [];
        try {
            DB::beginTransaction();

            foreach ($images as $image) {
                $type = $request->type;
                if (!isset($types[$type])) {
                    return $this->respondWithError('Invalid type: ' . $type);
                }
                $directory = $types[$type];
                $name = uploadImage($image, $directory, $request->type);
                if (!isset($name)) {
                    return $this->respondWithError('Error ooccured while uploading');
                }

                $othermedia = new OtherMedia();
                $othermedia->type = $request->type;
                $othermedia->image_url = asset('uploads/' . $directory . $name);
                $save = $othermedia->save();
                if (!$save) {
                    return $this->respondWithError('Error ooccured while uploading');
                    // throw new \Exception('Error Occured while image uploading');
                }
                $uploaded[] = $othermedia;
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->respondWithError($th->getMessage());
        }

        return $this->respondWithSuccess($uploaded, 'Image uploaded successfully', 'API_IMAGE_UPLOAD_SUCCESS');
    }
}
