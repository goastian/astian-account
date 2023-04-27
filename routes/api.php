<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\AuthorizationController;
use App\Http\Controllers\Sanctum\TokensController;
use App\Http\Controllers\User\RoleController;
use Illuminate\Support\Facades\Route;

//Auth
Route::post('login', [AuthorizationController::class,'store']);
Route::post('logout', [AuthorizationController::class, 'destroy']);
Route::get('about', [AuthenticatedSessionController::class, 'profile']);

//Tokens
Route::get('tokens',[TokensController::class, 'index'])->name('tokens.index');
Route::post('tokens', [TokensController::class, 'store'])->name('tokens.store');
Route::delete('tokens/clean', [TokensController::class, 'destroyAllTokens'])->name('tokens.clean');
Route::delete('tokens/{id}', [TokensController::class, 'destroy'])->name('tokens.destroy');

//Roles
Route::resource('roles', RoleController::class)->only('index');