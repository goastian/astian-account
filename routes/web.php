<?php

use App\Http\Controllers\Auth\OAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Route::get('/', [OAuthController::class, 'dashboard'])->name('dashboard');
Route::get('/clientes', [OAuthController::class, 'clientes'])->name('clientes');
Route::get('/tokens', [OAuthController::class, 'tokens'])->name('tokens');
Route::get('/session',[OAuthController::class, 'sessionState'])->name('session.update');

require __DIR__ . '/auth.php';
