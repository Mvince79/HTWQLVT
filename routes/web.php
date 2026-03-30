<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Driver\DriverController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;







Route::get('/', [HomeController::class, 'index'])->middleware('auth');




Route::prefix('/driver')
    ->middleware(['auth', 'role:driver'])
    ->name('driver.')
    ->group(function () {
        Route::get('/', [DriverController::class, 'index'])->name('index');
    });

Route::redirect('/login', '/admin/login')->name('login');




Route::post('/logout', function () {
    Auth::logout();
    return redirect('/admin/login');
})->name('logout');