<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ApiScheduleController;
use App\Http\Controllers\Api\V1\Auth\DriverAuthController;
use App\Http\Controllers\Api\V1\Auth\ManagerAuthController;
use App\Http\Controllers\Api\V1\ApiDriverController as ApiDriverController;
use App\Http\Controllers\Api\V1\ApiManagerController as ApiManagerController;
use App\Http\Controllers\Api\V1\ApiRouteController;
use App\Http\Controllers\Api\V1\ApiVehicleController;
use App\Http\Controllers\Api\V1\LogReportController;
use App\Http\Controllers\Api\V1\MediaController;
use App\Http\Middleware\ConvertFormData;

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
        Route::post('/login/web', [ManagerAuthController::class, 'webLogin']);
        Route::post('/get-code', [ManagerAuthController::class, 'getVerificationCode'])
            ->middleware('throttle:ratelimit');
        Route::post('/refresh', [ManagerAuthController::class, 'refresh']);
        Route::post('/logout', [ManagerAuthController::class, 'logout']);
        Route::post('/forget-password', [ManagerAuthController::class, 'forgetPassword']);

        // Manager Auth Middleware with jwt
        Route::middleware(['jwt.verify:manager'])->group(function () {
            Route::get('/profile', [ManagerAuthController::class, 'profile']);
            Route::post('/profile/upload', [ApiManagerController::class, 'profileUpload']);
            // Route::post('/create-schedule', [ScheduleController::class, 'create']);

            Route::post('upload-media', [MediaController::class, 'uploadMedia']);

            // Schedule Api
            Route::apiResource('/schedule', ApiScheduleController::class);
            Route::get('/get-organization-data', [ApiScheduleController::class, 'getOrganizationData']);
            Route::put('schedules/publish', [ApiScheduleController::class, 'publish']);
            Route::put('schedules/draft', [ApiScheduleController::class, 'draft']);
            Route::get('schedules/published/{date}', [ApiScheduleController::class, 'getPublishedScheduleByDate']);
            Route::get('schedules/created/{date}', [ApiScheduleController::class, 'getCreatedScheduleByDate']);

            // Driver Api
            Route::resource('/driver', ApiDriverController::class);
            Route::get('web/driver', [ApiDriverController::class ,'getDriver']);
            Route::post('web/driver', [ApiDriverController::class ,'storeWeb']);
            Route::put('web/driver/{id}', [ApiDriverController::class ,'updateWeb']);
            // Route::put('/driver/{id}/update', [ApiDriverController::class, 'update']);
            Route::get('/search/driver', [ApiDriverController::class, 'search']);

            // Vehicle Api
            Route::resource('/vehicle', ApiVehicleController::class);
            Route::get('web/vehicle', [ApiVehicleController::class, 'getVehicle']);
            Route::get('/search/vehicle', [ApiVehicleController::class, 'search']);

            // Route Api
            Route::resource('/route', ApiRouteController::class);
            Route::get('web/route', [ApiRouteController::class, 'getRoute']);
            Route::get('/search/route', [ApiRouteController::class, 'search']);

            // Log Report Api
            Route::post('/logreport', [LogReportController::class, 'index']);

            //main screen wrapper
            Route::get('/main-screen-wrapper', [ApiManagerController::class, 'mainScreenWrapper']);
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

        /**
         * Driver Auth Middleware with jwt
         */
        Route::middleware(['jwt.verify:driver'])->group(function () {
            Route::get('/profile', [DriverAuthController::class, 'profile']);
        });
    });
});
