<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Home\PlanController;

Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
Route::get('/pay', [PlanController::class, 'pay'])->name('pay.pay');
