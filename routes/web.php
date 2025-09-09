<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Home Route
Route::get('/', function () {
    return view('home');
});

// Login Route
Route::get('/login', function () {
    return view('/login');
})->name('login.page');

// Handle login (AJAX POST) and logout
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/login', [AuthController::class, 'showLoginPage'])->name('login.page');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// IRMS Dashboard
Route::get('/irms', function () {
    return view('irms/irms-layouts/dashboard');
})->name('irms.dashboard')->middleware('check.session');

Route::prefix('irms')->middleware('check.session')->group(function () {
    Route::get('/manage-users', fn() => view('irms/irms-layouts/manage-users'))->name('irms.manage-users');
    Route::get('/warehouse', fn() => view('irms/irms-layouts/warehouse'))->name('irms.warehouse');
    Route::get('/rack-locations', fn() => view('irms/irms-layouts/rack-locations'))->name('irms.locations');
});
