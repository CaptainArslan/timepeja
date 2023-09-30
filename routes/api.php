<?php

use App\Http\Controllers\Api\V1\Driver\ScheduleController as DriverScheduleController;
use App\Models\Pdf;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\PdfController;
use App\Http\Controllers\Api\V1\MediaController;
use App\Http\Controllers\Api\V1\ApiRouteController;
use App\Http\Controllers\Api\V1\LogReportController;
use App\Http\Controllers\Api\V1\ApiVehicleController;
use App\Http\Controllers\Api\V1\ApiScheduleController;
use App\Http\Controllers\Api\V1\VehicletypeController;
use App\Http\Controllers\Api\V1\OrganizationController as ApiOrganizationController;
use App\Http\Controllers\Api\V1\Auth\DriverAuthController;
use App\Http\Controllers\Api\V1\Auth\ManagerAuthController;
use App\Http\Controllers\Api\V1\ApiDriverController as ApiDriverController;
use App\Http\Controllers\Api\V1\ApiManagerController as ApiManagerController;
use App\Http\Controllers\Api\V1\Auth\PassengerAuthController;
use App\Http\Controllers\Api\V1\LocationController;
use App\Http\Controllers\Api\V1\Passenger\RouteController;
use App\Http\Controllers\Api\V1\Passenger\ScheduleController as PassengerScheduleController;
use App\Http\Controllers\Api\V1\PassengerController;
use App\Http\Controllers\Api\V1\PassengerRequestController;
use App\Http\Controllers\Api\V1\RequestController as ApiRequestController;

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
            Route::resource('/schedule', ApiScheduleController::class);
            Route::group(['prefix' => 'schedule'], function () {

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
            Route::get('/drivers/pdf', [ApiDriverController::class, 'createPdf']);
            Route::get('/search/driver', [ApiDriverController::class, 'search']);


            // Driver api for web
            Route::group(['prefix' => 'web/driver'], function () {
                Route::get('/', [ApiDriverController::class, 'getDriver']);
                Route::post('/', [ApiDriverController::class, 'storeWeb']);
                Route::put('/{id}', [ApiDriverController::class, 'updateWeb']);
            });


            // Vehicle Api
            Route::resource('/vehicle', ApiVehicleController::class);
            Route::get('/vehicles/pdf', [ApiVehicleController::class, 'createPdf']);
            Route::get('/search/vehicle', [ApiVehicleController::class, 'search']);


            // Vehicle apo for web
            Route::group(['prefix' => 'web/vehicle'], function () {
                Route::get('/', [ApiVehicleController::class, 'getVehicle']);
                Route::post('/', [ApiVehicleController::class, 'storeWeb']);
                Route::put('/{id}', [ApiVehicleController::class, 'updateWeb']);
            });


            // Organization Api
            Route::get('/get-all-organizations', [ApiOrganizationController::class, 'index']);
            Route::get('/organization/{code}', [ApiOrganizationController::class, 'show']);
            Route::post('/organization/deactivation/code', [ApiOrganizationController::class, 'deactivateCode']);
            Route::post('/organization/deactivate', [ApiOrganizationController::class, 'deactivate']);

            // Route Api
            Route::resource('/route', ApiRouteController::class);
            Route::get('/routes/pdf', [ApiRouteController::class, 'createPdf']);
            Route::get('web/route', [ApiRouteController::class, 'getRoute']);
            Route::get('/search/route', [ApiRouteController::class, 'search']);

            // Log Report Api
            Route::post('/logreport', [LogReportController::class, 'index']);
            Route::get('/logreport/pdf', [PdfController::class, 'logReport']);

            //main screen wrapper
            Route::get('/main-screen-wrapper', [ApiManagerController::class, 'wrapper']);
            Route::get('/log-report-wrapper', [ApiManagerController::class, 'wrapper']);

            // get tranport user requests
            Route::get('/requests', [ApiRequestController::class, 'index']);
            Route::get('/requests/past', [ApiRequestController::class, 'past']);
            Route::get('/requests/dissapproved', [ApiRequestController::class, 'disapproved']);
            Route::post('/request/store', [ApiRequestController::class, 'store']);
            Route::get('/request/{id}', [ApiRequestController::class, 'show']);
            Route::delete('/requests/delete', [ApiRequestController::class, 'delete']);
            Route::put('/requests/approve', [ApiRequestController::class, 'approveRequests']);
            Route::put('/requests/dissapprove', [ApiRequestController::class, 'dissapproveRequests']);
            Route::put('/requests/meet-personally', [ApiRequestController::class, 'meetPersonallyRequests']);

            Route::get('/locations', [LocationController::class, 'index']);
            Route::get('/locations/{id}', [LocationController::class, 'show']);
        });
    });

    // Driver Apis
    Route::prefix('v1/driver')->name('driver.')->group(function () {
        // Driver Auth
        Route::post('/register', [DriverAuthController::class, 'register']);
        Route::post('/login', [DriverAuthController::class, 'login']);
        Route::post('/get-code', [DriverAuthController::class, 'getVerificationCode'])
            ->middleware('throttle:ratelimit');
        Route::post('/refresh', [DriverAuthController::class, 'refresh']);
        Route::post('/forget-password', [DriverAuthController::class, 'forgetPassword']);

        /**
         * Driver Auth Middleware with jwt
         */
        Route::middleware(['jwt.verify:driver'])->group(function () {

            Route::get('/profile', [DriverAuthController::class, 'driverProfile']);
            Route::post('/logout', [DriverAuthController::class, 'logout']);

            Route::put('/online', [DriverScheduleController::class, 'online']);
            Route::put('/offline', [DriverScheduleController::class, 'offline']);

            Route::get('/schedule/incoming/{date}', [DriverScheduleController::class, 'index']);
            Route::put('/schedule/start/{id}', [DriverScheduleController::class, 'startTrip']);
            Route::put('/schedule/end/{id}', [DriverScheduleController::class, 'endTrip']);
            Route::put('/schedule/delay/{id}', [DriverScheduleController::class, 'delayTrip']);
        });
    });

    // Passenger apis
    Route::prefix('v1/passenger')->name('passenger.')->group(function () {
        // Driver Auth
        Route::post('/register', [PassengerAuthController::class, 'register']);
        Route::post('/login', [PassengerAuthController::class, 'login']);
        Route::post('/get-code', [PassengerAuthController::class, 'getVerificationCode'])
            ->middleware('throttle:ratelimit');
        Route::post('/refresh', [PassengerAuthController::class, 'refresh']);
        Route::post('/forget-password', [PassengerAuthController::class, 'forgetPassword']);

        /**
         * Driver Auth Middleware with jwt
         */
        Route::middleware(['jwt.verify:passenger'])->group(function () {
            Route::get('/profile', [PassengerAuthController::class, 'profile']);
            Route::post('/logout', [PassengerAuthController::class, 'logout']);

            Route::post('upload-media', [MediaController::class, 'uploadMedia']);

            // Passenger Request Api
            Route::group(['prefix' => 'requests', 'name' => 'requests'], function () {
                // get tranport user requests
                Route::get('/', [PassengerRequestController::class, 'index']);
                Route::get('/{id}', [PassengerRequestController::class, 'show']);
                Route::post('/store', [PassengerRequestController::class, 'store']);
                Route::put('/{id}', [PassengerRequestController::class, 'update']);
                Route::delete('/{id}', [PassengerRequestController::class, 'destroy']);
                // Route::post('/', [ApiVehicleController::class, 'storeWeb']);
                // Route::put('/{id}', [ApiVehicleController::class, 'updateWeb']);
            });
            Route::get('/schedules/{id}/{date}', [PassengerScheduleController::class, 'index']);
            Route::post('/add-favorites-routes', [RouteController::class, 'addFavoriteRoute']);
            Route::post('/remove-favorites-routes', [RouteController::class, 'removeFavoriteRoute']);

            Route::post('update-phone', [PassengerController::class, 'updatePhone']);
        });
    });
});
