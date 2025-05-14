<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Home\HomeController;
use App\Http\Controllers\Web\Home\PlanController;

Route::get("/", [HomeController::class, 'homePage'])->name('home.page');

Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
