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
use App\Http\Controllers\Irms\RsJobLocController;
use App\Http\Controllers\Irms\RsGoodsReceivingController;
use App\Http\Controllers\Irms\RsGoodsDispatchingController;
use App\Http\Controllers\Irms\RsGoodsRelocatingController;
use App\Http\Controllers\Irms\RsTransController;
use App\Http\Controllers\Irms\ItemInquiryController;
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

Route::prefix('irms')->middleware(['auth', 'marketing.restriction'])->group(function () {
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
    Route::get('/manage-users/user/{userid}', [RsUserController::class, 'show'])->name('rsusers.show');


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
    Route::get('/rack-locations/map-grid', [RsLocationController::class, 'rackMapGrid'])->name('racklocations.mapgrid');
    Route::post('/rack-locations/map-grid', [RsLocationController::class, 'rackMapGrid'])->name('racklocations.mapgrid');
    Route::post('/rack-locations/rack-items', [RsLocationController::class, 'rackItems'])->name('racklocations.rackitems');
    Route::patch('/rack-locations/update-quarantine', [RsLocationController::class, 'updateQuarantine'])->name('racklocations.quarantine');

    //Item Locations
    Route::get('/item-locations', [RsItemLocController::class, 'index'])->name('irms.itemlocations');
    Route::get('/item-locations/item-list', [RsItemLocController::class, 'itemList']);
    Route::get('/item-locations/{job}', [RsItemLocController::class, 'showJobDetails'])->name('itemloc.showJobDetails');
    Route::post('/item-locations/job-exists', [RsItemLocController::class, 'jobExists'])->name('itemloc.jobExists');

    //Job Locations
    Route::get('/job-locations', [RsJobLocController::class, 'index'])->name('irms.joblocations');
    Route::get('/job-locations/job-list', [RsJobLocController::class, 'jobList']);
    Route::get('/job-locations/{job}', [RsJobLocController::class, 'showJobDetails'])->name('jobloc.showJobDetails');

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
   
    // Goods Relocating
    Route::get('/relocate', [RsGoodsRelocatingController::class, 'index'])->name('goodsrelocating.index');
    Route::get('/goods-relocating/bays', [RsGoodsRelocatingController::class, 'getBays'])->name('irms.goods-relocating.bays');

    Route::get('/goods-relocating/locations', [RsGoodsRelocatingController::class, 'getLocations'])->name('irms.goods-relocating.locations');
    Route::get('/goods-relocating/pallets', [RsGoodsRelocatingController::class, 'getPallets'])->name('irms.goods-relocating.pallets');
    Route::get('/goods-relocating/jobs', [RsGoodsRelocatingController::class, 'getJobs'])->name('irms.goods-relocating.jobs');
    Route::get('/goods-relocating/item', [RsGoodsRelocatingController::class, 'getItem'])->name('irms.goods-relocating.item');
    Route::post('/goods-relocating/move', [RsGoodsRelocatingController::class, 'moveItem'])->name('irms.goods-relocating.move');
    Route::get('/irms/get-pallet-suggestions', [RsGoodsRelocatingController::class, 'getPalletSuggestions'])->name('irms.getPalletSuggestions');

    //Transactions
    // Route::get('/transactions', fn() => view('irms/irms-layouts/transactions'))->name('irms.transactions');
    Route::get('/transactions', [RsTransController::class, 'index'])->name('irms.transactions');
    Route::get('/transactions/transaction-list', [RsTransController::class, 'transactionList']);
    

    //ITEm-Inquiry
    Route::get('/item-inquiry', [ItemInquiryController::class, 'index'])->name('irms.item-inquiry');
    Route::get('/irms/item-inquiry/search', [ItemInquiryController::class, 'search'])->name('irms.item-inquiry.search');
    Route::get('/item-inquiry/pdf/summary', [ItemInquiryController::class, 'downloadSummaryPdf'])->name('irms.item-inquiry.pdf.summary');
    Route::get('/item-inquiry/pdf/detailed', [ItemInquiryController::class, 'downloadDetailedPdf'])->name('irms.item-inquiry.pdf.detailed');
    Route::get('/item-inquiry/report-list', [ItemInquiryController::class, 'reportList'])->name('irms.item-inquiry.report-list');
    Route::get('/item-inquiry/item-suggestions', [ItemInquiryController::class, 'itemSuggestions'])->name('irms.item-inquiry.item-suggestions');


    // // User Profile (move this to the bottom and add a constraint)
    // Route::get('/{userid}', [RsUserProfileController::class, 'show'])->where('userid', '[A-Za-z0-9]+')->name('irms.userprofile');
    // Route::post('/user-profile/{userid}/update', [RsUserProfileController::class, 'update'])->name('user-profile.update');
    // Route::post('/user-profile/{userid}/change-password', [RsUserProfileController::class, 'changePassword'])->name('user-profile.change-password');

    //Reports
    Route::get('/stickering-report/preview', [ReportController::class, 'stickeringPreview']) ->name('irms.stickering-report.preview');
    Route::get('/stickering-report/pdf', [ReportController::class, 'stickeringPdf'])->name('irms.stickering-report.pdf');
    Route::get('/quarantine-report/preview', [ReportController::class, 'quarantinePreview']) ->name('irms.quarantine-report.preview');
    Route::get('/quarantine-report/pdf', [ReportController::class, 'quarantinePdf'])->name('irms.quarantine-report.pdf');
    Route::get('/quarantine-report/preview', [ReportController::class, 'quarantinePreview']) ->name('irms.quarantine-report.preview');
    Route::get('/wip-report/preview', [ReportController::class, 'wipPreview']) ->name('irms.wip-report.preview');
    Route::get('/wip-report/pdf', [ReportController::class, 'wipPdf'])->name('irms.wip-report.pdf');
    
});

// Profile routes require login but are not subject to marketing.restriction.
Route::prefix('irms')->middleware('auth')->group(function () {
    Route::get('/{userid}', [RsUserProfileController::class, 'show'])->where('userid', '[A-Za-z0-9]+')->name('irms.userprofile');
    Route::post('/user-profile/{userid}/update', [RsUserProfileController::class, 'update'])->name('user-profile.update');
    Route::post('/user-profile/{userid}/change-password', [RsUserProfileController::class, 'changePassword'])->name('user-profile.change-password');
});


