<?php

namespace App\Http\Middleware;

use Closure;
use ApiHelper;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtMiddleware
{
    public function handle($request, Closure $next, $guard)
    {
        // $uid = $request->header('uid');
        $Authorization = $request->header('Authorization');
        $authorization = $request->header('authorization');
        if (!$Authorization || !$authorization) {
            return ApiHelper::respondWithError('Required headers not present. Authorization is missing!');
        }

        try {
            JWTAuth::parseToken()->authenticate($guard);
        } catch (Exception $e) {
            // dd($request->header('Authorization'));
            if ($e instanceof TokenInvalidException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token is Invalid'
                ], 401);
            } elseif ($e instanceof TokenExpiredException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token is Expired'
                ], 401);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Authorization Token not found'
                ], 401);
            }
        }
        return $next($request);
    }
}
