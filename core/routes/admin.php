<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\SettingsController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\CustomerController;
use App\Http\Controllers\admin\ContactController;
use App\Http\Controllers\admin\ProfitController;
use App\Http\Controllers\admin\SliderController;
use App\Http\Controllers\admin\PackController;

Route::prefix('admin')->middleware('admin')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index']);

    // Orders
    Route::prefix('orders')->controller(OrderController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/approve/{id}', 'approve');
        Route::post('/cancel/{id}', 'cancel');
        Route::post('/delivered/{id}', 'delivered');
    });

    // Contacts
    Route::prefix('contacts')->controller(ContactController::class)->group(function () {
        Route::get('/', 'index');
    });

    // Customers
    Route::prefix('customers')->controller(CustomerController::class)->group(function () {
        Route::get('/', 'index');              
        Route::get('/create', 'create');      
        Route::post('/store', 'store');        
        Route::get('/edit/{id}', 'edit');     
        Route::post('/update/{id}', 'update'); 
        Route::get('/delete/{id}', 'delete');  
        Route::get('/make-admin/{id}',  'makeAdmin');
        Route::get('/make-user/{id}',  'makeUser');
    });

    // Settings
    Route::get('/settings', [SettingsController::class, 'index']);
    Route::post('/settings', [SettingsController::class, 'update']);

    // Products
    Route::prefix('product')->controller(ProductController::class)->group(function () {
        Route::get('/', 'index');               
        Route::get('/create', 'create');       
        Route::post('/store', 'store');        
        Route::get('/edit/{id}', 'edit');       
        Route::post('/update/{id}', 'update'); 
        Route::get('/delete/{id}', 'delete'); 
    });

     // Pack
    Route::prefix('pack')->controller(PackController::class)->group(function () {
        Route::get('/', 'index');               
        Route::get('/create', 'create');       
        Route::post('/store', 'store');        
        Route::get('/edit/{id}', 'edit');       
        Route::post('/update/{id}', 'update'); 
        Route::get('/delete/{id}', 'delete'); 
    });

    // Categories
    Route::prefix('category')->controller(CategoryController::class)->group(function () {
        Route::get('/', 'index');        
        Route::get('/create', 'create');      
        Route::post('/store', 'store');       
        Route::get('/edit/{id}', 'edit');  
        Route::post('/update/{id}', 'update');  
        Route::get('/delete/{id}', 'delete');   
    });

    // Profit-Loss
    Route::prefix('profit_loss')->controller(ProfitController::class)->group(function () {
        Route::get('/', 'index');
    });

    // Sliders
    Route::prefix('sliders')->controller(SliderController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/create', 'create');       
        Route::post('/store', 'store');        
        Route::get('/edit/{id}', 'edit');       
        Route::post('/update/{id}', 'update'); 
        Route::get('/delete/{id}', 'delete'); 
    });

});