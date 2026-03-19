<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DriverController;

// Controller
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

<<<<<<< HEAD
// ADMIN
Route::get('/admin', [AdminController::class, 'index']);
Route::get('login', function () {
    return view('auth/login');
});
// CLIENT
Route::get('/client', [DriverController::class, 'index']);
=======
// Admin controller
use App\Http\Controllers\Admin\AdminController;

Route::get('/', [HomeController::class, 'index'])->middleware(['auth']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin
Route::prefix('/admin')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/', [AdminController::class, 'index']);
    });

// Driver
Route::prefix('/driver')
    ->middleware(['auth', 'role:driver'])
    ->group(function () {
        Route::get('/', function () {
            return view('welcome');
        });
    });
>>>>>>> 0055d0b ([Auth][PHP] - Login: Thêm chức năng login, logout và phân quyền người dùng)
