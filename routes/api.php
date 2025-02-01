<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Api\V1\PdfController;
use App\Http\Controllers\Api\V1\MediaController;
use App\Http\Controllers\Api\V1\LocationController;
use App\Http\Controllers\Api\V1\LogReportController;
use App\Http\Controllers\Api\V1\PassengerController;
use App\Http\Controllers\Api\V1\ApiVehicleController;
use App\Http\Controllers\Api\V1\ApiScheduleController;
use App\Http\Controllers\Api\V1\Manager\DriverController as ManagerDriverController;
use App\Http\Controllers\Api\V1\Manager\VehicletypeController as ManagerVehicletypeController;
use App\Http\Controllers\Api\V1\Manager\VehicleController as ManagerVehicleController;
use App\Http\Controllers\Api\V1\Auth\DriverAuthController;
use App\Http\Controllers\Api\V1\PassengerRequestController;
use App\Http\Controllers\Api\V1\Auth\PassengerAuthController;
use App\Http\Controllers\Api\V1\RequestController as ApiRequestController;
use App\Http\Controllers\Api\V1\ApiDriverController as ApiDriverController;
use App\Http\Controllers\Api\V1\Manager\AuthController as ManagerAuthController;
use App\Http\Controllers\Api\V1\OrganizationController as ApiOrganizationController;
use App\Http\Controllers\Api\V1\Driver\ScheduleController as DriverScheduleController;
use App\Http\Controllers\Api\V1\Manager\DashboardController as ManagerDashboardController;
use App\Http\Controllers\Api\V1\Manager\OrganizationController as ManagerOrganizationController;
use App\Http\Controllers\Api\V1\Manager\ProfileController as ManagerProfileController;
use App\Http\Controllers\Api\V1\Manager\RouteController as ManagerRouteController;
use App\Http\Controllers\Api\V1\Manager\ScheduleController as ManagerScheduleController;
use App\Http\Controllers\Api\V1\Passenger\RouteController as PassengerRouteController;
use App\Http\Controllers\Api\V1\Passenger\ScheduleController as PassengerScheduleController;

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
    Route::prefix('v1')->group(function () {
        Route::prefix('manager')->name('manager.')->group(function () {
            Route::post('/register', [ManagerAuthController::class, 'register']);
            Route::post('/login', [ManagerAuthController::class, 'login']);
            Route::post('/login/web', [ManagerAuthController::class, 'webLogin']);
            Route::post('/get-code', [ManagerAuthController::class, 'getCode']);
            Route::post('/forget-password', [ManagerAuthController::class, 'forgetPassword']);

            Route::middleware(['jwt.verify:manager'])->group(function () {
                Route::post('/refresh', [ManagerAuthController::class, 'refresh']);
                Route::post('/logout', [ManagerAuthController::class, 'logout']);

                Route::get('profile/', [ManagerProfileController::class, 'index']);
                Route::post('profile/upload', [ManagerProfileController::class, 'upload']);
                Route::put('profile/update', [ManagerProfileController::class, 'update']);

                //main screen wrapper
                Route::get('/main-screen-wrapper', [ManagerDashboardController::class, 'index']);
                Route::get('/log-report-wrapper', [ManagerDashboardController::class, 'index']);

                // Driver Api
                Route::get('/driver', [ManagerDriverController::class, 'index']);
                Route::post('/driver', [ManagerDriverController::class, 'store']);
                Route::get('/driver/{id}', [ManagerDriverController::class, 'show']);
                Route::put('/driver/{id}', [ManagerDriverController::class, 'update']);
                Route::delete('/driver/{id}', [ManagerDriverController::class, 'destroy']);
                Route::get('/drivers/pdf', [ManagerDriverController::class, 'createPdf']);

                // Vehicle Api
                Route::get('vehicle-types', [ManagerVehicletypeController::class, 'index']);
                Route::get('/vehicle', [ManagerVehicleController::class, 'index']);
                Route::post('/vehicle', [ManagerVehicleController::class, 'store']);
                Route::get('/vehicle/{id}', [ManagerVehicleController::class, 'show']);
                Route::put('/vehicle/{id}', [ManagerVehicleController::class, 'update']);
                Route::delete('/vehicle/{id}', [ManagerVehicleController::class, 'destroy']);
                Route::get('/vehicles/pdf', [ManagerVehicleController::class, 'createPdf']);

                // Route Api
                Route::get('/route', [ManagerRouteController::class, 'index']);
                Route::post('/route', [ManagerRouteController::class, 'store']);
                Route::get('/route/{id}', [ManagerRouteController::class, 'show']);
                Route::put('/route/{id}', [ManagerRouteController::class, 'update']);
                Route::delete('/route/{id}', [ManagerRouteController::class, 'destroy']);
                Route::get('/routes/pdf', [ManagerRouteController::class, 'createPdf']);

                // Organization Api
                Route::get('/get-all-organizations', [ManagerOrganizationController::class, 'index']);
                Route::get('/organization/{code}', [ManagerOrganizationController::class, 'show']);
                Route::post('/organization/deactivation/code', [ManagerOrganizationController::class, 'deactivateCode']);
                Route::post('/organization/deactivate', [ManagerOrganizationController::class, 'deactivate']);

                // Upload Media Api
                Route::get('/get-organization-data', [ManagerScheduleController::class, 'getOrganizationData']);

                // Schedule Api
                // Route::resource('/schedule', ApiScheduleController::class);
                Route::get('/schedules', [ManagerScheduleController::class, 'index']);
                Route::post('/schedules', [ManagerScheduleController::class, 'store']);
                
                Route::get('/schedule/{id}', [ApiScheduleController::class, 'show']);
                Route::put('/schedule/{id}', [ApiScheduleController::class, 'update']);
                Route::delete('/schedule/{id}', [ApiScheduleController::class, 'destroy']);
                Route::get('/schedules/active', [ApiScheduleController::class, 'activeVehicle']);
                Route::post('/schedule/replicate', [ApiScheduleController::class, 'replicate']);
                Route::put('/schedules/publish', [ApiScheduleController::class, 'publish']);
                Route::put('/schedules/draft', [ApiScheduleController::class, 'draft']);
                Route::get('/schedules/published/{date}', [ApiScheduleController::class, 'getPublishedScheduleByDate']);
                Route::get('/schedules/created/{date}', [ApiScheduleController::class, 'getCreatedScheduleByDate']);
                Route::get('/created-schedule/pdf/{date}', [PdfController::class, 'createdSchedule']);
                Route::get('/published-schedule/pdf/{date}', [PdfController::class, 'publishedSchedule']);

                // Log Report Api
                Route::post('/logreport', [LogReportController::class, 'index']);
                Route::get('/logreport/pdf', [PdfController::class, 'logReport']);
                Route::post('/get-user-request/pdf', [PdfController::class, 'userRequests']);

                // get transport user requests
                Route::get('/requests', [ApiRequestController::class, 'index']);
                Route::post('/request/store', [ApiRequestController::class, 'store']);

                Route::get('/requests/past', [ApiRequestController::class, 'past']);
                Route::get('/request/{id}', [ApiRequestController::class, 'show']);
                Route::get('requests/search', [ApiRequestController::class, 'search']);
                Route::get('/request/code/{code}', [ApiRequestController::class, 'getRequestDetailByCode']);
                Route::delete('/requests/delete', [ApiRequestController::class, 'delete']);
                // Get disapproved requests
                Route::get('/requests/dissapproved', [ApiRequestController::class, 'disapproved']);

                // Approve, Disapprove, Meet Personally update status
                Route::put('/requests/approve', [ApiRequestController::class, 'approveRequests']);
                Route::put('/requests/dissapprove', [ApiRequestController::class, 'disapproveRequests']);
                Route::put('/requests/meet-personally', [ApiRequestController::class, 'meetPersonallyRequests']);

                Route::get('/locations', [LocationController::class, 'index']);
                Route::get('/locations/search', [LocationController::class, 'search']);
                Route::get('/locations/{id}', [LocationController::class, 'show']);
            });
        });

        // Driver Apis
        Route::prefix('driver')->name('driver.')->group(function () {
            Route::post('/register', [DriverAuthController::class, 'register']);
            Route::post('/login', [DriverAuthController::class, 'login']);
            Route::post('/get-code', [DriverAuthController::class, 'getVerificationCode'])
                ->middleware('throttle:ratelimit');
            Route::post('/refresh', [DriverAuthController::class, 'refresh']);
            Route::post('/forget-password', [DriverAuthController::class, 'forgetPassword']);

            Route::middleware(['jwt.verify:driver'])->group(function () {
                Route::get('/profile', [DriverAuthController::class, 'driverProfile']);
                Route::put('/profile/update', [ApiDriverController::class, 'profileUpdate']);

                Route::post('/logout', [DriverAuthController::class, 'logout']);
                Route::put('/online', [DriverScheduleController::class, 'online']);
                Route::put('/offline', [DriverScheduleController::class, 'offline']);

                Route::get('/schedule/incoming/{date}', [DriverScheduleController::class, 'index']);
                Route::get('/schedules/{date?}', [DriverScheduleController::class, 'schedules']);
                Route::post('/schedules/filter', [DriverScheduleController::class, 'filterSchedules']);

                Route::put('/schedule/start/{id}', [DriverScheduleController::class, 'startTrip']);
                Route::put('/schedule/end/{id}', [DriverScheduleController::class, 'endTrip']);
                Route::put('/schedule/delay/{id}', [DriverScheduleController::class, 'delayTrip']);

                Route::get('notifications', [DriverScheduleController::class, 'notifications']);
            });
        });

        // Passenger apis
        Route::prefix('passenger')->name('passenger.')->group(function () {
            Route::post('/register', [PassengerAuthController::class, 'register']);
            Route::post('/login', [PassengerAuthController::class, 'login']);
            Route::post('/get-code', [PassengerAuthController::class, 'getVerificationCode'])
                ->middleware('throttle:ratelimit');
            Route::post('/refresh', [PassengerAuthController::class, 'refresh']);
            Route::post('/forget-password', [PassengerAuthController::class, 'forgetPassword']);
            Route::middleware(['jwt.verify:passenger'])->group(function () {

                // Route::get('/profile', [PassengerAuthController::class, 'profile']);

                Route::group(['prefix' => 'profile'], function () {
                    Route::get('/', [PassengerAuthController::class, 'profile']);
                    Route::post('/upload', [PassengerAuthController::class, 'profileUpload']);
                    Route::put('/update', [PassengerAuthController::class, 'profileUpdate']);
                });

                Route::post('/logout', [PassengerAuthController::class, 'logout']);
                // Passenger Request Api

                Route::group(['prefix' => 'requests', 'name' => 'requests'], function () {
                    // get tranport user requests
                    Route::get('/', [PassengerRequestController::class, 'index']);
                    Route::get('/{id}', [PassengerRequestController::class, 'show']);
                    Route::post('/store', [PassengerRequestController::class, 'store']);
                    Route::put('/{id}', [PassengerRequestController::class, 'update']);
                    Route::delete('/{id}', [PassengerRequestController::class, 'destroy']);
                    Route::get('/code/{code}', [ApiRequestController::class, 'getRequestDetailByCode']);
                });

                Route::get('/schedules/{id}/{date?}', [PassengerScheduleController::class, 'index']);


                Route::get('/get-favorites-routes', [PassengerRouteController::class, 'getFavoriteRoute']);
                Route::post('/add-favorites-routes', [PassengerRouteController::class, 'addFavoriteRoute']);
                Route::post('/remove-favorites-routes', [PassengerRouteController::class, 'removeFavoriteRoute']);
                Route::post('update-phone', [PassengerController::class, 'updatePhone']);

                Route::get('/get-routes', [PassengerRouteController::class, 'getRoutes']);
                Route::get('/get-all-organizations', [ApiOrganizationController::class, 'index']);
                Route::get('/organization/{code}', [ApiOrganizationController::class, 'show']);
            });
        });

        Route::post('upload-media', [MediaController::class, 'upload']);
        Route::post('/contact-us', [ContactController::class, 'send'])->name('send');
    });

    // Driver api for web
    Route::prefix('v1/')->group(function () {
        Route::prefix('manager')->name('manager.')->group(function () {
            Route::get('web/driver/', [ApiDriverController::class, 'getDriver']);
            Route::post('web/driver/', [ApiDriverController::class, 'storeWeb']);
            Route::put('web/driver/{id}', [ApiDriverController::class, 'updateWeb']);
        });

        Route::put('profile/web/update', [ManagerProfileController::class, 'update']);

        Route::get('web/route', [ManagerRouteController::class, 'getRoute']);

        // Vehicle apo for web
        Route::group(['prefix' => 'web/vehicle'], function () {
            Route::get('/', [ApiVehicleController::class, 'getVehicle']);
            Route::post('/', [ApiVehicleController::class, 'storeWeb']);
            Route::put('/{id}', [ApiVehicleController::class, 'updateWeb']);
        });
    });
});
