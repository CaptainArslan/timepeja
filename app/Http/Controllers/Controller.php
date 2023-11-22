<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
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
}
