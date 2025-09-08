<?php

use Illuminate\Support\Facades\Route;

//Home Route
Route::get('/', function () {
    return view('home');
});

Route::get('/Login', function () {
    return view('/Login');
});
Route::redirect('/login', '/Login');

//IRMS Route
Route::get('/irms', function () {
    return view('/irms/home');
});

Route::prefix("irms")->group(function () {
    Route::get('/dashboard', function () {
        return view('/irms/dashboard');
    });

    Route::get('/manage-users', function () {
        return view('/irms/manage-users');
    });

    Route::get('/warehouse', function () {
        return view('/irms/warehouse');
    });

});


// Route::get('/users/{userid}', function ($userid) {
//     return $userid;
// });