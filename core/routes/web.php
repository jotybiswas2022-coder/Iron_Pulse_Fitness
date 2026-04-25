<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\user\UserController;
use App\Http\Controllers\user\OrderManageController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\user\SearchController;


Route::prefix('/user/order')->middleware('auth')->controller(OrderManageController::class)->group(function () {
    Route::post('/store', 'store');
});

Route::middleware('auth')->controller(UserController::class)->group(function () {
    Route::get('/cart', 'cart');
    Route::get('/add_cart/{id}', 'addcart');
    Route::get('/manage/{type}/{id}', 'manage');
    Route::get('/billing', 'billing');
    Route::get('/orders', 'orders');
    Route::post('/contactus', 'contactus');

});

Route::middleware('auth')->controller(SearchController::class)->group(function () {
    Route::get('/search', 'search');

});


Route::controller(SiteController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/pack/{id}', 'pack');
});

Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');

Auth::routes();

include('admin.php');
