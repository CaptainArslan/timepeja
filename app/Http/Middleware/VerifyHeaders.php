<?php

namespace App\Http\Middleware;

use ApiHelper;
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
        // $authorization = $request->header('authorization');

        if (!$uid && !$accessToken) {
            return ApiHelper::respondWithError('Required headers not present. uid or access-token is missing!');
        }

        // perform validation or decoding of the headers as required
        // ...
        return $next($request);
    }
}
