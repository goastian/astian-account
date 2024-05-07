<?php

use App\Http\Controllers\Auth\AuthorizationController;
use App\Http\Controllers\Broadcasting\BroadcastController;
use App\Http\Controllers\Country\CountriesController;
use App\Http\Controllers\OAuth\CredentialsController;
use App\Http\Controllers\OAuth\PasspotConnectController;
use App\Http\Controllers\OAuth\ScopeController;
use App\Http\Controllers\Push\NotificationController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Role\RoleUserController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserNotificationController;
use App\Http\Controllers\User\UserRoleController;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;

/**
 * Gateways to grant access
 */
Route::prefix('gateway')->group(function () {

    Route::get('/check-authentication', [PasspotConnectController::class, 'check_authentication']);
    Route::get('/check-scope', [PasspotConnectController::class, 'check_scope']);
    Route::get('/check-scopes', [PasspotConnectController::class, 'check_scopes']);
    Route::get('/check-client-credentials', [PasspotConnectController::class, 'check_client_credentials']);
    Route::get('/token-can', [PasspotConnectController::class, 'token_can']);
    Route::get('/user', [PasspotConnectController::class, 'auth']);
    Route::post('/send-notification', [PasspotConnectController::class, 'send_notification']);
    Route::post('/logout', [AuthorizationController::class, 'destroy']);

})->middleware('verify.account', 'verify.credentials');

/**
 * Oauth Routes to get credentials
 */
Route::prefix('oauth')->group(function () {
    Route::post('/token', [AccessTokenController::class, 'issueToken'])
        ->name('passport.token')
        ->middleware('authorize');

    Route::get('/scopes', [ScopeController::class, 'all'])
        ->name('scopes.index')
        ->middleware('wants.json');

    Route::delete('/credentials/revoke', [CredentialsController::class, "revokeCredentials"])
        ->name('passport.revoke-credentials');
});

/**
 *
 * Admin Routes to manages users
 *
 */
Route::group([
    'prefix' => 'admin',
    'middleware' => ['verify.account', 'verify.credentials', 'wants.json'],

], function () {
    Route::resource('roles', RoleController::class)->except('create', 'edit');
    Route::resource('roles.users', RoleUserController::class)->only('index');

    Route::delete('users/{user}/disable', [UserController::class, 'disable'])->name('users.disable');
    Route::get('users/{id}/enable', [UserController::class, 'enable'])->name('users.enable');
    Route::resource('users', UserController::class)->except('edit', 'create', 'destroy');
    Route::resource('users.roles', UserRoleController::class)->only('index', 'store', 'destroy');
});

/**
 * Routes to send notifications
 */
Route::group([
    'prefix' => 'notifications',
    'middleware' => ['verify.account', 'verify.credentials', 'wants.json'],

], function () {
    Route::get('/', [UserNotificationController::class, 'index'])->name('notifications.index');
    Route::post('push', [NotificationController::class, 'push'])->name('notifications.push');
    Route::get('/unread', [UserNotificationController::class, 'show_unread_notifications'])->name('notifications.unread');
    Route::get('/{notification}', [UserNotificationController::class, 'show'])->name('notifications.show');
    Route::post('/mark_as_read', [UserNotificationController::class, 'mark_as_read_notifications'])->name('notifications.read_all');
    Route::post('/{notification}', [UserNotificationController::class, 'mark_as_read_notification'])->name('notifications.read');
    Route::delete('/clean', [UserNotificationController::class, 'clean'])->name('notifications.clean');
    Route::delete('/{notification}', [UserNotificationController::class, 'destroy'])->name('notifications.destroy');
});

/**
 * rutas que permiten administrar los canales de difusion dentro
 * del sistema a traves de eventos
 */

Route::resource('broadcasts', BroadcastController::class)
    ->only('index', 'store', 'destroy')
    ->middleware('wants.json');

/**
 * Locations
 */
Route::group([
    'prefix' => 'locations',
], function () {

    Route::resource('countries', CountriesController::class)->only('index');
});
