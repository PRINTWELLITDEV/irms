<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Irms\RsUserController;
use App\Http\Controllers\Irms\RsWhseController;
use App\Http\Controllers\Irms\RsBayLocController;
use App\Http\Controllers\Irms\RsLocationController;


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

// Session check route
Route::get('/irms/session', function () {
    $sessionId = request()->cookie(config('session.cookie'));
    $sessionExists = \DB::table('sessions')->where('id', $sessionId)->exists();
    return response()->json(['valid' => $sessionExists && auth()->check()]);
});

// Dashboard (protected)
Route::get('/irms', function () {
    return view('irms.irms-layouts.dashboard');
})->name('dashboard')->middleware('auth');

Route::prefix('irms')->middleware('check.session')->group(function () {
    // User management
    Route::get('/manage-users', [RsUserController::class, 'index'])->name('rsusers.index');
    Route::post('/manage-users/store', [RsUserController::class, 'store'])->name('rsusers.store');
    Route::put('/manage-users/update', [RsUserController::class, 'update'])->name('rsusers.update');

    // Warehouse
    Route::get('/warehouse', [RsWhseController::class, 'index'])->name('warehouse.index');
    Route::post('/warehouse/store', [RsWhseController::class, 'store'])->name('warehouse.store');
    Route::put('/warehouse/update', [RsWhseController::class, 'update'])->name('warehouse.update');
    // Route::get('/warehouse/{rswhse}', [RsWhseController::class, 'getWarehouseInfo']);

    // Bay location
    Route::get('/bay-locations', [RsBayLocController::class, 'index'])->name('baylocs.index');
    Route::post('/bay-locations/store', [RsBayLocController::class, 'store'])->name('baylocs.store');

    //Rack Locations
    Route::get('/rack-locations', [RsLocationController::class, 'index'])->name('racklocations.index');
    Route::post('/rack-locations/store', [RsLocationController::class, 'store'])->name('racklocations.store');

    //Item Locations 
    Route::get('/item-locations', fn() => view('irms/irms-layouts/item-locations'))->name('irms.itemlocations');
    

    // Others
    Route::get('/whse-goodsreceiving', fn() => view('irms/irms-layouts/whse-goodsreceiving'))->name('irms.whse-goodsreceiving');
    Route::get('/whse-goodsdispatching', fn() => view('irms/irms-layouts/whse-goodsdispatching'))->name('irms.whse-goodsdispatching');
    // Route::get('/rack-locations', fn() => view('irms/irms-layouts/rack-locations'))->name('irms.racklocations');
});
