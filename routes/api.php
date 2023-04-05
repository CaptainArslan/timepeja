<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\ManagerAuthController;
use App\Http\Controllers\Api\V1\Auth\DriverAuthController;
use App\Http\Controllers\Api\V1\DriverController as ApiDriverController;
use App\Http\Controllers\Api\V1\ScheduleController as ApiScheduleController;

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
        Route::post('/refresh', [ManagerAuthController::class, 'refresh']);
        Route::post('/logout', [ManagerAuthController::class, 'logout']);
        Route::post('/forget-password', [ManagerAuthController::class, 'forgetPassword']);
        Route::middleware(['jwt.verify:manager'])->group(function () {
            Route::get('/profile', [ManagerAuthController::class, 'profile']);
            Route::get('/get-organization-data', [ApiScheduleController::class, 'getOrganizationData']);
            // Route::post('/create-schedule', [ApiScheduleController::class, 'create']);

            /**
             * Schedule Api
             */
            Route::apiResource('/schedule', ApiScheduleController::class);
            Route::put('schedules/publish', [ApiScheduleController::class, 'publish']);
            Route::put('schedules/draft', [ApiScheduleController::class, 'draft']);
            Route::get('schedules/{date}', [ApiScheduleController::class, 'getScheduleByDate']);

            /**
             * Driver api
             */
            Route::apiResource('/driver', ApiDriverController::class);
        });
    });

    Route::prefix('v1/driver')->name('driver.')->group(function () {
        // Driver Auth
        Route::post('/register', [DriverAuthController::class, 'register']);
        Route::post('/login', [DriverAuthController::class, 'login']);
        Route::post('/get-code', [DriverAuthController::class, 'getVerificationCode'])
            ->middleware('throttle:ratelimit');
        Route::post('/refresh', [DriverAuthController::class, 'refresh']);
        Route::post('/logout', [DriverAuthController::class, 'logout']);
        Route::post('/forget-password', [DriverAuthController::class, 'forgetPassword']);
        Route::middleware(['jwt.verify:driver'])->group(function () {
            Route::get('/profile', [DriverAuthController::class, 'profile']);
        });
    });
});
