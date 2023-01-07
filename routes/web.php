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
Route::get('/register', function () { return view('auth.register'); })->name('register');
Route::get('/home', function () { return view('dashboard.dashboard'); })->name('home');
Route::get('/manager', function () { return view('dashboard.dashboard'); })->name('manager');
Route::get('/vehicle', function () { return view('dashboard.dashboard'); })->name('vehicle');
Route::get('/driver', function () { return view('dashboard.dashboard'); })->name('driver');
Route::get('/passenger ', function () { return view('dashboard.dashboard'); })->name('passenger');
Route::get('/route ', function () { return view('dashboard.dashboard'); })->name('route');
