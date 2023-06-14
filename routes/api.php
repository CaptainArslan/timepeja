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
use App\Http\Controllers\Api\V1\PdfController;
use App\Http\Controllers\Api\V1\VehicletypeController;
use App\Models\Pdf;

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
            Route::group(['prefix' => 'profile'], function () {
                Route::get('/', [ManagerAuthController::class, 'profile']);
                Route::post('/upload', [ApiManagerController::class, 'profileUpload']);
                Route::put('/update', [ApiManagerController::class, 'profileUpdate']);
                Route::put('/web/update', [ApiManagerController::class, 'profileUpdateWeb']);
            });

            // Route::post('/create-schedule', [ScheduleController::class, 'create']);

            // Upload Media Api
            Route::post('upload-media', [MediaController::class, 'uploadMedia']);

            // Get all organization data
            Route::get('/get-organization-data', [ApiScheduleController::class, 'getOrganizationData']);

            // Schedule Api
            Route::group(['prefix' => 'schedule'], function () {
                Route::apiResource('/', ApiScheduleController::class);
                Route::post('/replicate', [ApiScheduleController::class, 'replicate']);
            });

            Route::group(['prefix' => 'schedules'], function () {
                Route::put('/publish', [ApiScheduleController::class, 'publish']);
                Route::put('/draft', [ApiScheduleController::class, 'draft']);
                Route::get('/published/{date}', [ApiScheduleController::class, 'getPublishedScheduleByDate']);
                Route::get('/created/{date}', [ApiScheduleController::class, 'getCreatedScheduleByDate']);
            });

            // Vehicle Type
            Route::get('vehicle-types', [VehicleTypeController::class, 'index']);

            // Driver Api
            Route::resource('/driver', ApiDriverController::class);
            Route::get('/search/driver', [ApiDriverController::class, 'search']);
            // Driver api for web
            Route::group(['prefix' => 'web/driver'], function () {
                Route::get('/', [ApiDriverController::class, 'getDriver']);
                Route::post('/', [ApiDriverController::class, 'storeWeb']);
                Route::put('/{id}', [ApiDriverController::class, 'updateWeb']);
            });


            // Vehicle Api
            Route::resource('/vehicle', ApiVehicleController::class);
            Route::get('/search/vehicle', [ApiVehicleController::class, 'search']);
            // Vehicle apo for web
            Route::group(['prefix' => 'web/vehicle'], function () {
                Route::get('/', [ApiVehicleController::class, 'getVehicle']);
                Route::post('/', [ApiVehicleController::class, 'storeWeb']);
                Route::put('/{id}', [ApiVehicleController::class, 'updateWeb']);
            });

            // Route Api
            Route::resource('/route', ApiRouteController::class);
            Route::get('web/route', [ApiRouteController::class, 'getRoute']);
            Route::get('/search/route', [ApiRouteController::class, 'search']);

            // Log Report Api
            Route::get('/logreport', [LogReportController::class, 'index']);
            Route::get('/logreport/pdf', [PdfController::class, 'logReport']);

            //main screen wrapper
            Route::get('/main-screen-wrapper', [ApiManagerController::class, 'wrapper']);
            Route::get('/log-report-wrapper', [ApiManagerController::class, 'wrapper']);
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
