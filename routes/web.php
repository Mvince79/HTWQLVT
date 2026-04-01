<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Livewire\Driver\Dashboard;
use Illuminate\Support\Facades\Auth;



// Route::get('/', [HomeController::class, 'index'])->middleware('auth');
Route::get('/', [HomeController::class, 'index'])->middleware('auth:web');



// DRIVER
Route::middleware(['auth:web', 'role:driver'])->group(function () {
    Route::get('/driver/dashboard', Dashboard::class)
        ->name('driver.dashboard');
});

// LOGOUT
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/admin/login'); // chuẩn filament
})->name('logout');