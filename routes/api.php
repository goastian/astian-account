<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Asset\RoomController;
use App\Http\Controllers\User\UserRoleController;
use App\Http\Controllers\Asset\CalendarController;
use App\Http\Controllers\Asset\CategoryController;
use App\Http\Controllers\Sanctum\TokensController;
use App\Http\Controllers\Auth\AuthorizationController;
use App\Http\Controllers\Asset\CategoryCalendarController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


//Auth
Route::post('login', [AuthorizationController::class,'store']);
Route::post('logout', [AuthorizationController::class, 'destroy']);
Route::get('about', [AuthenticatedSessionController::class, 'profile']);

//Tokens
Route::get('tokens', [TokensController::class, 'index'])->name('tokens.index');
Route::post('tokens', [TokensController::class, 'store'])->name('tokens.store');
Route::delete('tokens/clean', [TokensController::class, 'destroyAllTokens'])->name('tokens.clean');
Route::delete('tokens/{id}', [TokensController::class, 'destroy'])->name('tokens.destroy');

//Roles
Route::resource('roles', RoleController::class)->only('index');

//Employees
Route::delete('users/{user}/disable', [UserController::class, 'disable'])->name('users.disable');
Route::get('users/{id}/enable', [UserController::class, 'enable'])->name('users.enable');
Route::resource('users', UserController::class)->except('edit', 'create', 'destroy');
Route::resource('users.roles', UserRoleController::class)->only('index', 'store', 'destroy');


//categories
Route::delete('categories/{category}/disable', [CategoryController::class, 'disable'])->name('categories.disable');
Route::get('categories/{id}/enable', [CategoryController::class, 'enable'])->name('categories.enable');
Route::resource('categories', CategoryController::class)->except('edit','create');
Route::resource('categories.calendars', CategoryCalendarController::class)->except('edit', 'create','destroy');

//rooms
Route::delete('rooms/{room}/disable', [RoomController::class, 'disable'])->name('rooms.disable');
Route::get('rooms/{id}/enable', [RoomController::class, 'enable'])->name('rooms.enable');
Route::resource('rooms', RoomController::class)->except('edit', 'create');



//calendar
Route::resource('calendars', CalendarController::class)->only('index');


