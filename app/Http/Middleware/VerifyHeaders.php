<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $uid = $request->header('uid');
        $accessToken = $request->header('access-token');
        $authorization = $request->header('authorization');

        if (!$uid || !$accessToken || !$authorization) {
            return response()->json(['message' => 'Required headers not present'], 400);
        }

        // perform validation or decoding of the headers as required
        // ...
        return $next($request);
    }
}
