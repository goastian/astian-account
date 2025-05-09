<?php

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
require __DIR__ . '/users.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/oauth.php'; 
require __DIR__ . '/webhook.php'; 

Route::get("/", function () {
    return view('landing.home');
});

Route::get("/{any}", function () {
    return view('app');
})->where('any', '^(?!api).*$');
