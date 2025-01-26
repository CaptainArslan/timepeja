<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\MediaUploadRequest;
use Symfony\Component\HttpFoundation\Response;

class MediaController extends BaseController
{
    public function upload(MediaUploadRequest $request): JsonResponse
    {
        $paths = [];

        try {
            // Check if the request has files
            if (!$request->hasFile('media')) {
                return $this->respondWithError('No media file provided.', Response::HTTP_BAD_REQUEST);
            }

            foreach ($request->file('media') as $key => $media) {
                // Check if the file is valid
                if (!$media->isValid()) {
                    return $this->respondWithError("Invalid media file: {$media->getClientOriginalName()}.", Response::HTTP_BAD_REQUEST);
                }

                try {
                    $path = $media->store('media', 'public');
                    $paths[$key] = Storage::url($path);
                } catch (\Exception $e) {
                    return $this->respondWithError("Failed to upload file: {$media->getClientOriginalName()}. " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
        } catch (\Exception $e) {
            return $this->respondWithError('Unexpected error occurred: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if (empty($paths)) {
            return $this->respondWithError('No files were successfully uploaded.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->respondWithSuccess($paths, 'Media uploaded successfully.', 'FILE_UPLOAD_SUCCESS');
    }
}
