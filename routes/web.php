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
Route::get('/oauth', [OAuthController::class, 'dashboard'])->name('dashboard');
Route::get('/oauth/clientes', [OAuthController::class, 'clientes'])->name('clientes');

require __DIR__ . '/auth.php'; 

Route::get('/', function () {
    return request()->user() ? redirect()->route('dashboard') : redirect()->route('login');
});