<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Irms\IrmsController;
use App\Http\Controllers\Irms\RsSiteController;
use App\Http\Controllers\Irms\RsUserProfileController;
use App\Http\Controllers\Irms\RsUserController;
use App\Http\Controllers\Irms\RsWhseController;
use App\Http\Controllers\Irms\RsBayLocController;
use App\Http\Controllers\Irms\RsLocationController;
use App\Http\Controllers\Irms\RsItemLocController;
use App\Http\Controllers\Irms\RsGoodsReceivingController;
use App\Http\Controllers\Irms\RsGoodsDispatchingController;
use App\Http\Controllers\Irms\RsTransController;
use App\Http\Controllers\Irms\ReportController;
use App\Http\Controllers\HomeController;

// Home route
// Route::get('/', function () {
//     return view('home');
// })->name('home');

// Route::get('/', function () {
//     if (auth()->check()) {
//         return redirect('/irms');
//     }else{
//         return view('home');
//     }
// })->name('home');
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/irms');
    } else {
        return app(HomeController::class)->index();
    }
})->name('home');

// Register
// if (config('app.env') !== 'production') {
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register'])->name('register.submit');
// }

// Password Reset Routes
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

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
})->name('/')->middleware('auth');

Route::prefix('irms')->middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', [IrmsController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/refresh', [IrmsController::class, 'refreshDashboardData'])->name('dashboard.refresh');
    
    // Site management
    Route::get('/manage-sites', [RsSiteController::class, 'index'])->name('sites.index');
    Route::get('/manage-sites/site-list', [RsSiteController::class, 'siteList']);
    Route::post('/manage-sites/store', [RsSiteController::class, 'store'])->name('sites.store');

    // User management
    Route::get('/manage-users', [RsUserController::class, 'index'])->name('rsusers.index');
    Route::get('/manage-users/user-list', [RsUserController::class, 'userlist']);
    Route::post('/manage-users/store', [RsUserController::class, 'store'])->name('rsusers.store');
    Route::put('/manage-users/update', [RsUserController::class, 'update'])->name('rsusers.update');

    // Warehouse
    Route::get('/warehouse', [RsWhseController::class, 'index'])->name('warehouse.index');
    Route::get('/warehouse/whse-list', [RsWhseController::class, 'whselist']);
    Route::post('/warehouse/store', [RsWhseController::class, 'store'])->name('warehouse.store');
    Route::put('/warehouse/update', [RsWhseController::class, 'update'])->name('warehouse.update');

    // Bay location
    Route::get('/bay-locations', [RsBayLocController::class, 'index'])->name('baylocs.index');
    Route::get('/bay-locations/bay-list', [RsBayLocController::class, 'bayList'])->name('baylist');
    Route::post('/bay-locations/store', [RsBayLocController::class, 'store'])->name('baylocs.store');

    //Rack Locations
    Route::get('/rack-locations', [RsLocationController::class, 'index'])->name('racklocations.index');
    Route::get('/rack-locations/rack-list', [RsLocationController::class, 'rackList'])->name('racklocations.racklist');
    Route::post('/rack-locations/store', [RsLocationController::class, 'store'])->name('racklocations.store');
    Route::post('/rack-locations/map-grid', [RsLocationController::class, 'rackMapGrid'])->name('racklocations.mapgrid');
    Route::post('/rack-locations/rack-items', [RsLocationController::class, 'rackItems'])->name('racklocations.rackitems');
    Route::patch('/rack-locations/update-quarantine', [RsLocationController::class, 'updateQuarantine'])->name('racklocations.quarantine');

    //Item Locations
    Route::get('/item-locations', [RsItemLocController::class, 'index'])->name('irms.itemlocations');
    Route::get('/item-locations/item-list', [RsItemLocController::class, 'itemList']);
    Route::get('/item-locations/{job}', [RsItemLocController::class, 'showJobDetails'])->name('itemloc.showJobDetails');
    Route::post('/item-locations/job-exists', [RsItemLocController::class, 'jobExists'])->name('itemloc.jobExists');

    // Goods Receiving
    Route::get('/receiving', [RsGoodsReceivingController::class, 'index'])->name('goodsreceiving.index');
    Route::post('/receiving/process', [RsGoodsReceivingController::class, 'process'])->name('goodsreceiving.process');
    Route::post('/receiving/job-item-details', [RsGoodsReceivingController::class, 'getJobItemDetails'])->name('goodsreceiving.jobitemdetails');
    Route::post('/receiving/rsloc-list', [RsGoodsReceivingController::class, 'getRsLocList'])->name('goodsreceiving.rsloclist');
    Route::post('/receiving/process-goods-received', [RsGoodsReceivingController::class, 'processGoodsReceived'])->name('goodsreceiving.processreceived');

    // Goods Dispatching
    Route::get('/dispatching', [RsGoodsDispatchingController::class, 'index'])->name('goodsdispatching.index');
    Route::post('/dispatching/process', [RsGoodsDispatchingController::class, 'process'])->name('goodsdispatching.process');
    Route::post('/dispatching/job-item-details', [RsGoodsDispatchingController::class, 'getJobItemDetails'])->name('goodsdispatching.jobitemdetails');
    Route::post('/dispatching/item-in-rsloc-list', [RsGoodsDispatchingController::class, 'getItemInRsLocList'])->name('goodsdispatching.iteminrsloclist');
    Route::post('/dispatching/process-goods-dispatch', [RsGoodsDispatchingController::class, 'processGoodsDispatch'])->name('goodsdispatching.processdispatch');

    //Transactions
    // Route::get('/transactions', fn() => view('irms/irms-layouts/transactions'))->name('irms.transactions');
    Route::get('/transactions', [RsTransController::class, 'index'])->name('irms.transactions');
    Route::get('/transactions/transaction-list', [RsTransController::class, 'transactionList']);

    // User Profile (move this to the bottom and add a constraint)
    Route::get('/{userid}', [RsUserProfileController::class, 'show'])->where('userid', '[A-Za-z0-9]+')->name('irms.userprofile');
    Route::post('/user-profile/{userid}/update', [RsUserProfileController::class, 'update'])->name('user-profile.update');
    Route::post('/user-profile/{userid}/change-password', [RsUserProfileController::class, 'changePassword'])->name('user-profile.change-password');

    //Reports
    Route::get('/stickering-report/preview', [ReportController::class, 'stickeringPreview']) ->name('irms.stickering-report.preview');
    Route::get('/stickering-report/pdf', [ReportController::class, 'stickeringPdf'])->name('irms.stickering-report.pdf');
    Route::get('/quarantine-report/preview', [ReportController::class, 'quarantinePreview']) ->name('irms.quarantine-report.preview');
    Route::get('/quarantine-report/pdf', [ReportController::class, 'quarantinePdf'])->name('irms.quarantine-report.pdf');
});


