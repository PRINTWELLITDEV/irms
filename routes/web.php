<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Irms\RsUserController;
use App\Http\Controllers\Irms\RsWhseController;
use App\Http\Controllers\Irms\RsBayLocController;
use App\Http\Controllers\Irms\RsLocationController;
use App\Http\Controllers\Irms\RsGoodsReceivingController;
use App\Http\Controllers\Irms\RsGoodsDispatchingController;


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

    // Bay location
    Route::get('/bay-locations', [RsBayLocController::class, 'index'])->name('baylocs.index');
    Route::post('/bay-locations/store', [RsBayLocController::class, 'store'])->name('baylocs.store');

    //Rack Locations
    Route::get('/rack-locations', [RsLocationController::class, 'index'])->name('racklocations.index');
    Route::post('/rack-locations/store', [RsLocationController::class, 'store'])->name('racklocations.store');

    //Item Locations
    Route::get('/item-locations', fn() => view('irms/irms-layouts/item-locations'))->name('irms.itemlocations');

    // Goods Receiving
    Route::get('/receiving', [RsGoodsReceivingController::class, 'index'])->name('goodsreceiving.index');
    Route::post('/receiving/process', [RsGoodsReceivingController::class, 'process'])->name('goodsreceiving.process');
    Route::post('/receiving/job-item-details', [RsGoodsReceivingController::class, 'getJobItemDetails'])->name('goodsreceiving.jobitemdetails');
    Route::post('/receiving/rsloc-list', [RsGoodsReceivingController::class, 'getRsLocList'])->name('goodsreceiving.rsloclist');
    Route::post('/receiving/process-goods-received', [RsGoodsReceivingController::class, 'processGoodsReceived'])->name('goodsreceiving.processreceived');

    // Goods Dispatching
    Route::get('/whse-goodsdispatching', [RsGoodsDispatchingController::class, 'index'])->name('goodsdispatching.index');
    Route::post('/whse-goodsdispatching/process', [RsGoodsDispatchingController::class, 'process'])->name('goodsdispatching.process');


});


