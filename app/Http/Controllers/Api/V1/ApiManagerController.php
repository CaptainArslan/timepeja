<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiManagerController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profileUpload(Request $request): JsonResponse
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

            if ($request->hasFile('profile_picture')) {
                removeImage($manager->picture_name, '/managers/profiles/');
            }
            $image = uploadImage($request->file('profile_picture'), '/managers/profiles/');

            $manager->picture = $image;
            if ($manager->save()) {
                return $this->respondWithSuccess($manager->picture, 'Profile Uploaded', 'PROFILE_UPLOADED');
            } else {
                return $this->respondWithError('Profile not uploaded');
            }
        } catch (\Throwable $th) {
            return $this->respondWithError('Error Occured while profile uploading');
        }

        // return $this->respondWithSuccess(null, 'Profile Uploaded', 'PROFILE_UPLOADED');
    }
}
