<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController; 
use App\Http\Controllers\User\UserScopeController; 
use App\Http\Controllers\Global\CountriesController;
use App\Http\Controllers\OAuth\ClientAdminController;
use App\Http\Controllers\OAuth\CredentialsController;
use App\Http\Controllers\Subscription\RoleController;
use App\Http\Controllers\Subscription\GroupController;
use App\Http\Controllers\Subscription\ScopeController;
use App\Http\Controllers\OAuth\AuthorizationController;
use App\Http\Controllers\Subscription\ServiceController;
use App\Http\Controllers\OAuth\PassportConnectController;
use App\Http\Controllers\User\UserNotificationController;
use App\Http\Controllers\Broadcasting\BroadcastController;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use App\Http\Controllers\Subscription\ServiceScopeController;
use App\Http\Controllers\OAuth\ScopeController as OauthScopeController;

/**
 * Gateways to grant access
 */
Route::prefix('gateway')->group(function () {

    Route::get('/check-authentication', [PassportConnectController::class, 'check_authentication']);
    Route::get('/check-scope', [PassportConnectController::class, 'check_scope']);
    Route::get('/check-scopes', [PassportConnectController::class, 'check_scopes']);
    Route::get('/check-client-credentials', [PassportConnectController::class, 'check_client_credentials']);
    Route::get('/token-can', [PassportConnectController::class, 'token_can']);
    Route::get('/user', [PassportConnectController::class, 'auth']);
    Route::post('/logout', [AuthorizationController::class, 'destroy']);

})->middleware(['verify.account', 'verify.credentials']);

/**
 * Oauth Routes to get credentials
 */
Route::prefix('oauth')->group(function () {
    Route::post('/token', [AccessTokenController::class, 'issueToken'])
        ->name('passport.token')
        ->middleware('authorize');

    Route::get('/scopes', [OauthScopeController::class, 'all'])
        ->name('scopes.index')
        ->middleware('wants.json');

    Route::delete('/credentials/revoke', [CredentialsController::class, "revokeCredentials"])
        ->name('passport.revoke-credentials');

    Route::resource('/clients', ClientAdminController::class);
});

/**
 *
 * Admin Routes to manages users
 *
 */
Route::group([
    'prefix' => 'admin',
    'middleware' => ['verify.account', 'verify.credentials'],

], function () {

    Route::resource('broadcasts', BroadcastController::class)->only('index', 'store', 'destroy');
    Route::resource('groups', GroupController::class)->except('edit', 'create');
    Route::resource('roles', RoleController::class)->except('create', 'edit');
    Route::resource('services', ServiceController::class)->except('create', 'edit');
    Route::resource('scopes', ScopeController::class)->except('create', 'edit');
    Route::resource('services.scopes', ServiceScopeController::class)->only('index');

    Route::delete('users/{user}/disable', [UserController::class, 'disable'])->name('users.disable');
    Route::get('users/{id}/enable', [UserController::class, 'enable'])->name('users.enable');
    Route::resource('users', UserController::class)->except('edit', 'create', 'destroy');

    Route::get('/users/{user}/scopes/history', [UserScopeController::class, 'history'])->name('users.scopes.history');
    Route::get('/users/{user}/scopes', [UserScopeController::class, 'index'])->name('users.scopes.index');
    Route::post('/users/{user}/scopes', [UserScopeController::class, 'store'])->name('users.scopes.store');
    Route::put('/users/{user}/scopes', [UserScopeController::class, 'revoke'])->name('users.scopes.revoke');
});

/**
 * Routes to send notifications
 */
Route::group([
    'prefix' => 'notifications',
    'middleware' => ['verify.account', 'verify.credentials', 'wants.json'],

], function () {
    Route::get('/', [UserNotificationController::class, 'index'])->name('notifications.index');
    Route::get('/unread', [UserNotificationController::class, 'show_unread_notifications'])->name('notifications.unread');
    Route::get('/{notification}', [UserNotificationController::class, 'show'])->name('notifications.show');
    Route::post('/mark_as_read', [UserNotificationController::class, 'mark_as_read_notifications'])->name('notifications.read_all');
    Route::post('/{notification}', [UserNotificationController::class, 'mark_as_read_notification'])->name('notifications.read');
    Route::delete('/clean', [UserNotificationController::class, 'clean'])->name('notifications.clean');
    Route::delete('/{notification}', [UserNotificationController::class, 'destroy'])->name('notifications.destroy');
});

Route::prefix('assets')->group(function () {
    Route::resource('countries', CountriesController::class)->only('index');
});