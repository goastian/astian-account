<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::get('about', [AuthenticatedSessionController::class, 'profile']);
