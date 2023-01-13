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
Route::get('/profile', function () { return view('auth.profile'); })->name('profile');
Route::get('/home', function () { return view('dashboard.dashboard'); })->name('home');
Route::get('/manager', function () { return view('manager.index');})->name('manager');
Route::get('/passenger', function () { return view('passenger.index');})->name('passenger');
Route::get('/driver', function () { return view('driver.index');})->name('driver');
Route::get('/vehicle', function () { return view('vehicle.index');})->name('vehicle');
Route::get('/route', function () { return view('route.index');})->name('route');
Route::get('/revenue', function () { return view('report.revenue');})->name('revenue');
Route::get('/expense', function () { return view('report.expense');})->name('expense');