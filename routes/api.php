<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Asset\CategoryController;
use App\Http\Controllers\Asset\RoomController; 
 
//categories
Route::delete('categories/{category}/disable', [CategoryController::class, 'disable'])->name('categories.disable');
Route::get('categories/{id}/enable', [CategoryController::class, 'enable'])->name('categories.enable');
Route::resource('categories', CategoryController::class)->except('edit','create');

//rooms
Route::delete('rooms/{room}/disable', [RoomController::class, 'disable'])->name('rooms.disable');
Route::get('rooms/{id}/enable', [RoomController::class, 'enable'])->name('rooms.enable');
Route::resource('rooms', RoomController::class)->except('edit', 'create');