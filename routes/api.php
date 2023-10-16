<?php

use App\Enum\EnumType;
use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\UserController; 
use App\Http\Controllers\Auth\TokensController;
use App\Http\Controllers\User\UserRoleController; 
use App\Http\Controllers\Auth\AuthorizationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController; 

//Valores absolutos 
Route::get('document-type', [EnumType::class, 'documento_type']);

Route::post('login', [AuthorizationController::class, 'store'])->name('signin');
Route::post('logout', [AuthorizationController::class, 'destroy']);
//Route::delete('tokens', [TokensController::class, 'destroyAllTokens'])->name('tokens.destroyAll');
//Route::resource('tokens', TokensController::class)->only('index', 'store', 'destroy');
 
Route::get('about', [AuthenticatedSessionController::class, 'profile']);
 
//Roles
Route::resource('roles', RoleController::class)->only('index');

//Employees
Route::delete('users/{user}/disable', [UserController::class, 'disable'])->name('users.disable');
Route::get('users/{id}/enable', [UserController::class, 'enable'])->name('users.enable');
Route::resource('users', UserController::class)->except('edit', 'create', 'destroy');
Route::resource('users.roles', UserRoleController::class)->only('index', 'store', 'destroy');
 