<?php

use App\Events\ExampleEvent;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\PassengerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//User Roles
// Route::resource('roles', 'RoleController');
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Auth::routes();

Route::group(['middleware' => 'auth'], function () {


    Route::get('clear-cache', function () {
        Artisan::call('optimize:clear');
        return back();
    });

    Route::get('storage-link', function () {
        Artisan::call('storage:link');
        dd("links Completed");
    });

    Route::get('queue-work', function () {
        Artisan::call('queue:work --stop-when-empty');
        dd("Queue working Completed");
    });

    Route::get('schedule-work', function () {
        Artisan::call('schedule:work');
        dd("Schedule stated");
    });
    Route::get('schedule-terminate', function () {
        Artisan::call('schedule:terminate');
        dd("Schedule Terminated");
    });

    Route::get('clear-route', function () {
        Artisan::call('route:clear');
        return back();
    });

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    // Manager
    Route::prefix('manager')->name('manager.')->group(function () {
        Route::match(['get', 'post'], '/', [ManagerController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/create', [ManagerController::class, 'create'])->name('create');
        Route::post('/store', [ManagerController::class, 'store'])->name('store');
        Route::post('/edit', [ManagerController::class, 'edit'])->name('edit');
        Route::post('/delete', [ManagerController::class, 'destroy'])->name('delete');
    });

    // Delete Organization
    Route::delete('/organizatrion/{id}', [ManagerController::class, 'deleteOrganization'])->name('delete.organization');

    /**
     * driver
     */
    // Route::resource('/driver', DriverController::class);
    Route::prefix('driver')->name('driver.')->group(function () {
        Route::match(['get', 'post'], '/', [DriverController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/create', [DriverController::class, 'create'])->name('create');
        Route::post('/store', [DriverController::class, 'store'])->name('store');
        Route::post('/edit', [DriverController::class, 'edit'])->name('edit');
        Route::post('/delete/{id}', [DriverController::class, 'destroy'])->name('delete');
        Route::post('/multidelete', [DriverController::class, 'multiDelete'])->name('multiDelete');
        Route::post('/pdf', [DriverController::class, 'printPdf'])->name('printPdf');
        Route::match(['get', 'post'], 'upcoming-trips', [DriverController::class, 'upcomingTrips'])->name('upcomingTrips');

        Route::get('get-org-driver/{id}', [CommonController::class, 'getDrivers'])->name('get-driver');
    });


    // Vehicle
    Route::prefix('vehicle')->name('vehicle.')->group(function () {
        Route::match(['get', 'post'], '/', [VehicleController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/create', [VehicleController::class, 'create'])->name('create');
        Route::post('/store', [VehicleController::class, 'store'])->name('store');
        Route::post('/edit', [VehicleController::class, 'edit'])->name('edit');
        Route::post('/delete', [VehicleController::class, 'destroy'])->name('delete');
        Route::post('multi-delete', [VehicleController::class, 'multiDelete'])->name('multiDelete');
    });

    Route::get('/active-vehicle', function () {
        return view('manager.active_vehicle');
    })->name('active_vehicle');

    /**
     * routes
     */
    Route::prefix('routes')->name('routes.')->group(function () {
        Route::match(['get', 'post'], '/', [RouteController::class, 'index'])->name('index');
        Route::post('/create', [RouteController::class, 'create'])->name('create');
        Route::post('/store', [RouteController::class, 'store'])->name('store');
        Route::post('/edit', [RouteController::class, 'edit'])->name('edit');
        Route::post('/delete', [RouteController::class, 'destroy'])->name('delete');
    });

    // Schedule
    Route::prefix('schedule')->name('schedule.')->group(function () {
        Route::get('/', [ScheduleController::class, 'index'])->name('index');
        Route::get('/create', [ScheduleController::class, 'create'])->name('create');
        Route::post('/store', [ScheduleController::class, 'store'])->name('store');
        Route::match(['get', 'post'], '/get-published-schedule', [ScheduleController::class, 'schedulePublished'])->name('published');
        Route::post('/draft-publish-schedule', [ScheduleController::class, 'publishDraftSchedule'])->name('publish');
        // Route::post('/edit', [RouteController::class, 'edit'])->name('edit');
        // Route::post('/delete', [RouteController::class, 'delete'])->name('delete');
    });

    // Passenger
    Route::prefix('passenger')->name('passenger.')->group(function () {
        Route::get('/', [PassengerController::class, 'index'])->name('index');
        Route::post('/store', [PassengerController::class, 'store'])->name('store');
        Route::delete('/delete/{id}', [PassengerController::class, 'destroy'])->name('delete');
        Route::put('/{id}', [PassengerController::class, 'update'])->name('update');
    });

    // Log Report
    Route::match(['get', 'post'], '/log/reports', [ManagerController::class, 'logReport'])->name('log.reports');

    // Settings
    Route::match(['get', 'put'], 'setting', [SettingController::class, 'updateOrCreate'])->name('setting.google');

    // User Approval
    Route::prefix('user')->name('user.')->group(function () {
        Route::match(['get', 'post'], '/awaiting/approvals', [ManagerController::class, 'awaitingApproval'])->name('awaiting');
        Route::match(['get', 'post'], '/approved', [ManagerController::class, 'approvedUser'])->name('approved');
        Route::match(['get', 'post'], '/disapproved', [ManagerController::class, 'disapprovedUser'])->name('disapproved');
        Route::match(['get', 'post'], '/pastuser', [ManagerController::class, 'pastUser'])->name('pastuser');
    });


    // Admin Profile
    Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
    Route::put('/profile/{user}', [HomeController::class, 'updateProfile'])->name('profile.update');

    // Route::get('/pdf', function () {
    //     return view('pdf.logreport');
    // });

    // Route::get('/transpot/schedule', function () { return view('manager.transport_scheduled'); })->name('transpot.schedule');
    // Route::get('/awaiting/approval', function () {
    //     return view('manager.approval.awaiting_approved_form');
    // })->name('awaiting.approval');
    // Route::get('/pastuser', function () {
    //     return view('manager.approval.pastuser');
    // })->name('pastuser');
    // Route::get('/user/disapproved', function () {
    //     return view('manager.approval.disapproved_user');
    // })->name('user.disapproved');

    Route::get('/user/approval', function () {
        return view('manager.approval.user_approval_form');
    })->name('user.approval');
    Route::get('/history', function () {
        return view('manager.history');
    })->name('history');
    Route::get('/transpot/users', function () {
        return view('manager.users.transport_users');
    })->name('transpot.users');


    /**
     * request
     */
    Route::get('/request', [RequestController::class, 'index'])->name('request');
    Route::get('/request/list', function () {
        return view('request.list');
    })->name('request.list');


    // Route::get('/passenger/list', function () { return view('passenger.passenger_list'); })->name('passenger_list');

    /**
     *
     */
    Route::get('/trans_routes', function () {
        return view('passenger.trans_routes');
    })->name('trans_routes');
    Route::get('/trans_schdule', function () {
        return view('passenger.trans_schdule');
    })->name('trans_schdule');
    Route::get('driver/notification', function () {
        return view('driver.notification');
    })->name('driver.notification');
    Route::get('driver/triphistory', function () {
        return view('driver.triphistory');
    })->name('driver.triphistory');
    Route::get('driver/tripstatus', function () {
        return view('driver.tripstatus');
    })->name('driver.tripstatus');
    // Route::get('/route', function () { return view('route.index');})->name('route');
    Route::get('/revenue', function () {
        return view('report.revenue');
    })->name('revenue');
    Route::get('/expense', function () {
        return view('report.expense');
    })->name('expense');

    /**
     *
     */
    Route::get('/history/Passenger-to-Passenger', function () {
        return view('history.passenger_to_passenger');
    })->name('bus.passenger');
    Route::get('/history/Bus-Passenger', function () {
        return view('history.bus_passenger');
    })->name('passenger.to.passenger');
    Route::get('/history/Customer-Trip', function () {
        return view('history.customer_trip');
    })->name('customer.trip');

    /**
     *
     */
    Route::get('/modules', function () {
        return view('admin.modules.index');
    })->name('modules.index');
    Route::get('/module-groups', function () {
        return view('admin.module-groups.index');
    })->name('module-groups.index');
    Route::get('/roles', function () {
        return view('admin.roles.index');
    })->name('roles.index');
    Route::get('/system-users', function () {
        return view('admin.users.index');
    })->name('users.index');
    Route::get('/permissions', function () {
        return view('admin.permissions.index');
    })->name('permissions.index');
    Route::get('/support', function () {
        return view('support.support');
    })->name('support');
    Route::get('/support/chat', function () {
        return view('support.index');
    })->name('support.chat');
    Route::get('/wallets', function () {
        return view('wallet.index');
    })->name('wallet');
    Route::get('/pdf-popup', function () {
        return view('manager.report.export.pdf_popup');
    })->name('pdf.popup');


    /**
     * ajax call request handle
     */
    Route::get('get-cities/{state_id?}', [CommonController::class, 'getCities'])->name('get_cities');
    Route::get('get-schedule-route-driver-vehicle/{org_id}', [
        CommonController::class, 'getScheduleRouteDriverVehicle'
    ])->name('get_schedule_route_driver_vehicle');
    Route::post('schedule/delete/{id}', [ScheduleController::class, 'destroy'])->name('schedule.delete');
    Route::get('get-schedule', [ScheduleController::class, 'getSchedule'])->name('getSchedule');
    Route::get('get-driver-vehicle-route', [ScheduleController::class, 'getDriverVehicleRoute'])->name('getDriverVehicleRoute');


    Route::get('example', function (){
        event(new ExampleEvent(rand(100, 999999)));
        dd('example events');
    });
});

