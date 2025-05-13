<?php

use App\Http\Controllers\Web\Home\HomeController;
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
require __DIR__ . '/web/users.php';
require __DIR__ . '/web/auth.php';
require __DIR__ . '/web/oauth.php';
require __DIR__ . '/web/webhook.php';
require __DIR__ . '/web/public.php';
require __DIR__ . '/web/settings.php';
require __DIR__ . '/web/admin.php';
require __DIR__ . '/web/partner.php';

Route::get("/", [HomeController::class, 'homePage'])->name('home.page');
