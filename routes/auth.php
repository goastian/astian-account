<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Setting\SettingController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\RegisterClientController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\AuthorizationModuloController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('forgot-password');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');

Route::get("/redirect_module", [AuthorizationModuloController::class, 'redirectModule'])->name('authorize.module');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

/**
 * Register user account
 */
if (config('system.enable_register_member', true)) {
    Route::get('/register', [RegisterClientController::class, 'register'])->name('register');
    Route::post('/register', [RegisterClientController::class, 'store']);
}

Route::group([
    'prefix' => 'settings',
    'as' => 'settings.',
], function () {
    Route::get('/', [SettingController::class, 'general'])->name('general');
    Route::get('/passport', [SettingController::class, 'passport'])->name('passport');
    Route::get('/email', [SettingController::class, 'email'])->name('email');
    Route::get('/user', [SettingController::class, 'user'])->name('user');
    Route::get('/routes', [SettingController::class, 'routes'])->name('routes');
    Route::get('/redis', [SettingController::class, 'redis'])->name('redis');
    Route::get('/queues', [SettingController::class, 'queues'])->name('queues');
    Route::get('/filesystem', [SettingController::class, 'filesystem'])->name('filesystem');
    Route::get('/payment', [SettingController::class, 'payment'])->name('payment');

    Route::put('/', [SettingController::class, 'update'])->name('update');
});