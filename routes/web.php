<?php

use App\Models\TransportManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\TransportManagerController;
use App\Http\Controllers\VehicleController;

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
})->name('login');
Route::get('/register', function () { return view('auth.register'); })->name('register');
Auth::routes();

Route::group(['middleware' => 'auth'], function(){
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('/managers', TransportManagerController::class);
    Route::resource('/routes', RouteController::class);
    Route::resource('/vehicles', VehicleController::class);
    
    // Route::get('/vehicle', function () { return view('vehicle.index');})->name('vehicle');
    Route::get('/profile', function () { return view('auth.profile'); })->name('profile');
    // Route::get('/manager', function () { return view('manager.index');})->name('manager');
    
    // Approval User
    Route::get('/awaiting/approvals', function () { return view('manager.approval.awaiting_approvals');})->name('awaiting.approvals');
    Route::get('/awaiting/approval', function () { return view('manager.approval.awaiting_approved_form');})->name('awaiting.approval');
    Route::get('/user/approved', function () { return view('manager.approval.approved_user');})->name('user.approved');
    Route::get('/user/disapproved', function () { return view('manager.approval.disapproved_user');})->name('user.disapproved');
    Route::get('/user/approval', function () { return view('manager.approval.user_approval_form');})->name('user.approval');


    Route::get('/history', function () { return view('manager.history');})->name('history');
    Route::get('/log/reports', function () { return view('manager.log_report');})->name('log.reports');
    Route::get('/schedule/creation', function () { return view('manager.schedule_creation');})->name('schedule.creation');
    Route::get('/schedule/publishes', function () { return view('manager.schedule_published');})->name('schedule.publishes');
    Route::get('/transpot/schedule', function () { return view('manager.transport_scheduled');})->name('transpot.schedule');
    Route::get('/transpot/users', function () { return view('manager.users.transport_users');})->name('transpot.users');
    Route::get('/passenger', function () { return view('passenger.index');})->name('passenger');
    Route::get('/passenger/list', function () { return view('passenger.passenger_list');})->name('passenger_list');
    Route::get('/trans_routes', function () { return view('passenger.trans_routes');})->name('trans_routes');
    Route::get('/trans_schdule', function () { return view('passenger.trans_schdule');})->name('trans_schdule');
    Route::prefix('driver')->group(function () {
        Route::get('/', function () { return view('driver.index');})->name('driver');
        Route::get('/notification', function () { return view('driver.notification');})->name('driver.notification');
        Route::get('/triphistory', function () { return view('driver.triphistory');})->name('driver.triphistory');
        Route::get('/trips', function () { return view('driver.trips');})->name('driver.trips');
        Route::get('/lists', function () { return view('driver.lists');})->name('driver.lists');
        Route::get('/tripstatus', function () { return view('driver.tripstatus');})->name('driver.tripstatus');
    });
    Route::get('/list', function () { return view('vehicle.vehicle_list');})->name('vehicle');
    
    // Route::get('/route', function () { return view('route.index');})->name('route');
    Route::get('/revenue', function () { return view('report.revenue');})->name('revenue');
    Route::get('/expense', function () { return view('report.expense');})->name('expense');
    Route::get('/history/Passenger-to-Passenger', function () { return view('history.passenger_to_passenger');})->name('bus.passenger');
    Route::get('/history/Bus-Passenger', function () { return view('history.bus_passenger');})->name('passenger.to.passenger');
    Route::get('/history/Customer-Trip', function () { return view('history.customer_trip');})->name('customer.trip');
    Route::get('/modules', function () { return view('admin.modules.index');})->name('modules.index');
    Route::get('/module-groups', function () { return view('admin.module-groups.index');})->name('module-groups.index');
    Route::get('/roles', function () { return view('admin.roles.index');})->name('roles.index');
    Route::get('/system-users', function () { return view('admin.users.index');})->name('users.index');
    Route::get('/permissions', function () { return view('admin.permissions.index');})->name('permissions.index');
    Route::get('/support', function () { return view('support.support');})->name('support');
    Route::get('/support/chat', function () { return view('support.index');})->name('support.chat');
    Route::get('/wallets', function () { return view('wallet.index');})->name('wallet');
});