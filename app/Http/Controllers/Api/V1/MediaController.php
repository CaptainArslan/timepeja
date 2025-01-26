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
                return $this->sendError('No media file provided.', Response::HTTP_BAD_REQUEST);
            }

            foreach ($request->file('media') as $media) {
                // Check if the file is valid
                if (!$media->isValid()) {
                    return $this->sendError("Invalid media file: {$media->getClientOriginalName()}.", Response::HTTP_BAD_REQUEST);
                }

                try {
                    $path = $media->store('media', 'public');
                    $paths[] = Storage::url($path); 
                } catch (\Exception $e) {
                    return $this->sendError("Failed to upload file: {$media->getClientOriginalName()}. " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
        } catch (\Exception $e) {
            return $this->sendError('Unexpected error occurred: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if (empty($paths)) {
            return $this->sendError('No files were successfully uploaded.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->sendResponse($paths, 'Media uploaded successfully.', 'API_IMAGE_UPLOAD_SUCCESS');
    }
}
