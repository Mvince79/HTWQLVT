<?php

use Illuminate\Support\Facades\Route;

// Controller
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

// Admin controller
use App\Http\Controllers\Admin\AdminController;

// Driver controller
use App\Http\Controllers\Driver\DriverController;

Route::get('/', [HomeController::class, 'index'])->middleware('auth');
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login')->name('login.submit');
    Route::post('/logout', 'logout')->name('logout');
});

// Admin
// Route::prefix('/admin')
//     ->middleware(['auth', 'role:admin'])
//     ->name('admin.')
//     ->group(function () {
//         Route::get('/', [AdminController::class, 'index'])->name('index');
//     });

// Driver
Route::prefix('/driver')
    ->middleware(['auth', 'role:driver'])
    ->name('driver.')
    ->group(function () {
        Route::get('/', [DriverController::class, 'index'])->name('index');
    });
