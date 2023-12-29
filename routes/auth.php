<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController; 
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisterClientController;
use Illuminate\Support\Facades\Route;
 
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create']);
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/register', [RegisterClientController::class, 'register'])->name('client.register');
Route::get('/verify/account', [RegisterClientController::class, 'verify_account'])->name('verify.account');