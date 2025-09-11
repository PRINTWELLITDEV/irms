<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RsUserController;

// Home route
Route::get('/', function () {
    return view('home');
})->name('home');

//if already logged in, redirect to irms dashboard
Route::get('login', [LoginController::class, 'showhomeForm'])->name('home');
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');

// Handle login and logout
Route::post('login', [LoginController::class, 'login'])->name('login.submit');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard (protected)

Route::get('/irms', function () {
    return view('irms.irms-layouts.dashboard');
})->name('dashboard')->middleware('auth');
Route::prefix('irms')->middleware('check.session')->group(function () {
    Route::get('/manage-users', [RsUserController::class, 'index'])->name('rsusers.index');
    Route::get('/warehouse', fn() => view('irms/irms-layouts/warehouse'))->name('irms.warehouse');
    Route::get('/rack-locations', fn() => view('irms/irms-layouts/rack-locations'))->name('irms.locations');
});
