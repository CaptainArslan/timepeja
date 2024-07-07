<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ApiHelper
{

    /**
     * @param $data
     * @param $message
     * @param $code
     * @param array $headers
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function respondWithSuccess($data = null, $message = null, $code = null, array $headers = [], int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ], $statusCode, $headers);
    }

    /**
     * Undocumented function
     *
     * @param [type] $message
     * @param integer $code
     * @return JsonResponse
     */
    public static function respondWithError($message = null, int $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }


    /**
     * @param $request
     * @param $user
     * @return true
     */
    public static function saveDeviceToken($request, $user): bool
    {
        Log::info('Device Token: ' . $request->device_token);
        if($request->device_token){
            $user->device_token = $request->device_token;
            $user->save();
        }
        return true;
    }
}
