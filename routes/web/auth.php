<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Auth\NewPasswordController;
use App\Http\Controllers\Web\Auth\RegisterClientController;
use App\Http\Controllers\Web\Auth\PasswordResetLinkController;
use App\Http\Controllers\Web\Auth\AuthorizationModuloController;
use App\Http\Controllers\Web\Auth\AuthenticatedSessionController;


Route::group([
    'prefix' => "auth",
], function () {

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('captcha');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('forgot-password');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->middleware('captcha')->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');

    Route::get("/redirect_module", [AuthorizationModuloController::class, 'redirectModule'])->name('authorize.module');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    /**
     * Register user account
     */
    if (config('routes.guest.register', true)) {
        Route::get('/register', [RegisterClientController::class, 'register'])->name('register');
        Route::post('/register', [RegisterClientController::class, 'store'])->middleware('captcha');
    }
});