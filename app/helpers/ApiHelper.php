<?php
class ApiHelper
{
    /**
     * Undocumented function
     *
     * @param [type] $data
     * @param [type] $message
     * @param [type] $code
     * @param array $headers
     * @param integer $statusCode
     * @return void
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
     * Undocumented function
     *
     * @param [type] $message
     * @param integer $code
     * @return void
     */
    public static function respondWithError($message = null, $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }


    public static function saveDeviceToken($request, $user){
        if($request->device_token){
            $user->device_token = $request->device_token;
            $user->save();
        }
    }
}
