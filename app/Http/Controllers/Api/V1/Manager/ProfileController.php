<?php

namespace App\Http\Controllers\Api\V1\Manager;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Manager\Profile\ProfileUpdateRequest;
use App\Http\Requests\Manager\Profile\ProfilePicUploadRequest;

class ProfileController extends Controller
{
    public function index(): JsonResponse
    {
        $manager = Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        return $this->respondWithSuccess(
            $manager->load('organization'),
            'Manager profile',
            'MANAGER_PROFILE'
        );
    }

    public function upload(ProfilePicUploadRequest $request): jsonResponse
    {
        $manager = Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        if ($request->picture) {
            Storage::delete($manager->picture);
        }

        $manager->update([
            'picture' => $request->picture ? $request->picture : $manager->picture_name,
        ]);

        return $this->respondWithSuccess($manager, 'Profile Picture Updated', 'PROFILE_PICTURE_UPDATED');
    }

    public function update(ProfileUpdateRequest $request): jsonResponse
    {
        $manager = Auth::guard('manager')->user();

        if (!$manager) {
            return $this->respondWithError('Manager not found');
        }

        $manager->update([
            'name' => $request->name ? $request->name : $manager->name,
            'phone' => $request->phone ? $request->phone : $manager->phone,
            'address' => $request->address ? $request->address : $manager->address,
        ]);

        return $this->respondWithSuccess($manager, 'Profile Updated', 'PROFILE_UPDATED');
    }
}
