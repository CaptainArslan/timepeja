<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\ManagerAuthController;
use App\Http\Controllers\Api\V1\Auth\DriverAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['middleware' => 'api'], function () {
    Route::prefix('v1/manager')->name('manager.')->group(function () {
        // Manager Auth
        Route::post('/register', [ManagerAuthController::class, 'register']);
        Route::post('/login', [ManagerAuthController::class, 'login']);
        Route::post('/get-code', [ManagerAuthController::class, 'getVerificationCode'])
        ->middleware('throttle:ratelimit');
        Route::post('/forget-password', [ManagerAuthController::class, 'forgetPassword']);
    });

    Route::prefix('v1/driver')->name('driver.')->group(function () {
        // Driver Auth
        Route::post('/register', [DriverAuthController::class, 'register']);
        Route::post('/login', [DriverAuthController::class, 'login']);
        Route::post('/get-code', [DriverAuthController::class, 'getVerificationCode'])
        ->middleware('throttle:ratelimit');
        Route::post('/forget-password', [DriverAuthController::class, 'forgetPassword']);
    });




    Route::group(['middleware' => ['jwt.verify']], function () {
        // Protected routes
    });
    Route::group(['middleware' => ['veify.header']], function () {
        // Protected routes
    });
});
