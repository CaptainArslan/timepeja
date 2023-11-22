<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    /**
     * success response method.
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message
        ];
        return response()->json($response, 200);
    }

    /**
     * return error response.
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error
        ];
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }


    /**
     * success response method.
     */
    public static function respondWithSuccess($data = null, $message = null, $code = null, $headers = [], $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ], $statusCode, $headers);
    }

    /**
     * return error response.
     */
    public static function respondWithError($message = null, $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }

    /**
     * success response method.
     */
    public static function respondWithDelete($message = null, $code = null, $headers = [], $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'code' => $code,
            'message' => $message,
        ], $statusCode, $headers);
    }

    /**
     * success response method.
     */
    public static function respondWithSuccessLogReport($data = null, $download_url = null, $message = null, $code = null, $headers = [], $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'download_url' => $download_url,
        ], $statusCode, $headers);
    }
}
