<?php

use App\Http\Controllers\Admin\CommercialObjectController;
use App\Http\Controllers\Admin\LoginController;
use Illuminate\Support\Facades\Route;

// Commercial unit admin (module:commercial-admin). Logins: php artisan user:create

Route::prefix('admin')->group(function () {
    Route::middleware('guest')->group(function () {
        // Named 'login' because Laravel's auth middleware redirects there.
        Route::get('login', [LoginController::class, 'show'])->name('login');
        Route::post('login', [LoginController::class, 'store'])->name('admin.login');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/', [CommercialObjectController::class, 'index'])->name('admin.objects.index');
        Route::put('/', [CommercialObjectController::class, 'update'])->name('admin.objects.update');
        Route::post('logout', [LoginController::class, 'destroy'])->name('admin.logout');
    });
});
