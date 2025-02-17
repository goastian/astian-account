<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Setting\CodeController;
use App\Http\Controllers\Setting\SettingController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\RegisterClientController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\AuthorizationModuloController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('forgot-password');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');

Route::get("/redirect_module", [AuthorizationModuloController::class, 'redirectModule'])->name('authorize.module');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

/**
 * Register user account
 */
if (settingItem('enable_register_member', true)) {
    Route::get('/register', [RegisterClientController::class, 'register'])->name('register');
    Route::post('/register', [RegisterClientController::class, 'store']);
}

/**
 * Check account
 */
Route::get('/verified-account', [RegisterClientController::class, 'verifiedAccount'])->name('verified-account');
Route::get('/verify/account', [RegisterClientController::class, 'verifyAccount'])->name('verify.account');
Route::get('/check-my-account', [RegisterClientController::class, 'formVerifyAccount'])->name('check.account');
Route::post('/send-verification-email', [RegisterClientController::class, 'sendVerificationEmail'])->name('send.verification.email');
/**
 * 2FA
 */
Route::get('/verify/2fa-factor', [CodeController::class, 'create'])->name('factor.email');
Route::post('/verify/2fa-factor', [CodeController::class, 'loginBy2FA'])->name('factor.email.login');

Route::post('/m2fa/authorize', [CodeController::class, 'requestToken2FA'])->name('m2fa.authorize');
Route::post('/m2fa/activate', [CodeController::class, 'factor2faEnableOrDisable'])->name('m2fa.activate');


Route::group([
    'prefix' => 'settings',
    'as' => 'settings.',
], function () {
    Route::get('/', [SettingController::class, 'general'])->name('general');
    Route::get('/passport', [SettingController::class, 'passport'])->name('passport');
    Route::get('/email', [SettingController::class, 'email'])->name('email');
    Route::get('/user', [SettingController::class, 'user'])->name('user');
    Route::get('/routes', [SettingController::class, 'routes'])->name('routes');

    Route::post('/', [SettingController::class, 'update'])->name('update');
});