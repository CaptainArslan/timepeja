<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () { return view('auth.login'); })->name('login');
Route::get('/login', function () { return view('auth.login');})->name('login');
Route::get('/register', function () { return view('auth.register'); })->name('register');
Route::get('/home', function () { return view('dashboard.dashboard'); })->name('home');
Route::get('/manager', function () { return view('admin.manager.index');})->name('manager');
Route::get('/passenger', function () { return view('admin.passenger.index');})->name('passenger');
Route::get('/driver', function () { return view('admin.driver.index');})->name('driver');
Route::get('/vehicle', function () { return view('admin.vehicle.index');})->name('vehicle');
Route::get('/route', function () { return view('admin.route.index');})->name('route');