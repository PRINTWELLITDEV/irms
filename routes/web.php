<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RsUserController;
use App\Http\Controllers\RsWhseController;
use App\Http\Controllers\RsBayLocController;

// Home route
Route::get('/', function () {
    return view('home');
})->name('home');

// if already logged in, redirect to irms dashboard
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
    // User management
    Route::get('/manage-users', [RsUserController::class, 'index'])->name('rsusers.index');
    Route::post('/manage-users/store', [RsUserController::class, 'store'])->name('RsUserController.store');

    // Warehouse
    Route::get('/warehouse', [RsWhseController::class, 'index'])->name('warehouse.index');
    Route::post('/warehouse/store', [RsWhseController::class, 'store'])->name('warehouse.store');
    Route::put('/irms/warehouse/update', [RsWhseController::class, 'update'])->name('warehouse.update');
    Route::get('/irms/warehouse/{rswhse}', [RsWhseController::class, 'getWarehouseInfo']);
    
    // Bay location
    Route::get('/bay-location', [RsBayLocController::class, 'index'])->name('bay-location.index');
    Route::post('/bay-location/store', [RsBayLocController::class, 'store'])->name('bay-location.store');

    // Others
    Route::get('/whse-goodreceiving', fn() => view('irms/irms-layouts/whse-goodreceiving'))->name('irms.whse-goodreceiving');
    Route::get('/rack-locations', fn() => view('irms/irms-layouts/rack-locations'))->name('irms.racklocations');
});
