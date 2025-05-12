<?php

use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\Web\Public\PlanController;


Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
