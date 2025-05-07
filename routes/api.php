<?php

use App\Http\Controllers\Manager\TransactionManagerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserGroupController;
use App\Http\Controllers\User\UserScopeController;
use App\Http\Controllers\Global\CountriesController;
use App\Http\Controllers\Setting\TerminalController;
use App\Http\Controllers\User\UserPaymentController;
use App\Http\Controllers\OAuth\ClientAdminController;
use App\Http\Controllers\OAuth\CredentialsController;
use App\Http\Controllers\Subscription\PlanController;
use App\Http\Controllers\Subscription\RoleController;
use App\Http\Controllers\Subscription\GroupController;
use App\Http\Controllers\Subscription\ScopeController;
use App\Http\Controllers\Subscription\PaymentController;
use App\Http\Controllers\Subscription\ServiceController;
use App\Http\Controllers\OAuth\PassportConnectController;
use App\Http\Controllers\User\UserNotificationController;
use App\Http\Controllers\Broadcasting\BroadcastController;
use App\Http\Controllers\Subscription\PlanPriceController;
use App\Http\Controllers\Subscription\PlanScopeController;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use App\Http\Controllers\Subscription\ServiceScopeController;
use App\Http\Controllers\User\PlanController as UserPlanController;
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
    Route::get('/user', [PassportConnectController::class, 'authenticated']);
    Route::get('/access', [PassportConnectController::class, 'access']);
    Route::post('/logout', [PassportConnectController::class, 'revokeAuthorization']);
});

/**
 * Oauth Routes to get credentials
 */
Route::group([
    'prefix' => 'oauth',
    'as' => 'oauth.'
], function () {
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
    'as' => 'admin.',
    'middleware' => ['wants.json'],

], function () {

    Route::resource('broadcasts', BroadcastController::class)->only('index', 'store', 'destroy');
    Route::resource('groups', GroupController::class)->except('edit', 'create');
    Route::resource('roles', RoleController::class)->except('create', 'edit');
    Route::resource('scopes', ScopeController::class)->only('index');

    Route::resource('services', ServiceController::class)->except('create', 'edit');
    Route::get('services/{service}/scopes', [ServiceScopeController::class, 'index'])->name('service.scopes.index');
    Route::post('services/{service}/scopes', [ServiceScopeController::class, 'assign'])->name('service.scopes.assign');
    Route::delete('services/{service}/scopes/{scope}', [ServiceScopeController::class, 'revoke'])->name('services.scopes.revoke');

    Route::delete('users/{user}/disable', [UserController::class, 'disable'])->name('users.disable');
    Route::get('users/{id}/enable', [UserController::class, 'enable'])->name('users.enable');
    Route::resource('users', UserController::class)->except('edit', 'create');

    Route::get('/users/{user}/scopes/history', [UserScopeController::class, 'history'])->name('users.scopes.history');
    Route::get('/users/{user}/scopes', [UserScopeController::class, 'index'])->name('users.scopes.index');
    Route::post('/users/{user}/scopes', [UserScopeController::class, 'assign'])->name('users.scopes.assign');
    Route::put('/users/{user}/scopes/{scope}', [UserScopeController::class, 'revoke'])->name('users.scopes.revoke');

    Route::get('/users/{user}/groups', [UserGroupController::class, 'index'])->name('users.groups.index');
    Route::post('/users/{user}/groups', [UserGroupController::class, 'assign'])->name('users.groups.assign');
    Route::delete('/users/{user}/groups/{group}', [UserGroupController::class, 'revoke'])->name('users.groups.revoke');

    Route::resource('/clients', ClientAdminController::class)->except('edit', 'create');

    Route::resource('/plans', PlanController::class)->except('edit', 'create');
    Route::delete('/plans/{plan}/scopes/{scope}', [PlanScopeController::class, 'revoke'])->name('plans.scopes.revoke');
    Route::delete('/plans/{plan}/prices/{price}', [PlanPriceController::class, 'destroy'])->name('plans.prices.destroy');

    Route::get('/transactions', [TransactionManagerController::class, 'index'])->name('transactions.index');
    Route::put('/transactions/{transaction}', [TransactionManagerController::class, 'activate'])->name('transactions.activate');

    Route::resource('terminals', TerminalController::class)->only('index', 'store');
});

/**
 * Routes to send notifications
 */
Route::group([
    'prefix' => 'notifications',
    'middleware' => ['wants.json'],

], function () {
    Route::get('/', [UserNotificationController::class, 'index'])->name('notifications.index');
    Route::get('/unread', [UserNotificationController::class, 'show_unread_notifications'])->name('notifications.unread');
    Route::get('/{notification}', [UserNotificationController::class, 'show'])->name('notifications.show');
    Route::post('/mark_as_read', [UserNotificationController::class, 'mark_as_read_notifications'])->name('notifications.read_all');
    Route::post('/{notification}', [UserNotificationController::class, 'mark_as_read_notification'])->name('notifications.read');
    Route::delete('/clean', [UserNotificationController::class, 'clean'])->name('notifications.clean');
    Route::delete('/{notification}', [UserNotificationController::class, 'destroy'])->name('notifications.destroy');
});

Route::group([
    'prefix' => 'public',
    'as' => 'public.'
], function () {
    Route::resource('/countries', CountriesController::class)->only('index');
    Route::get('/plans', [UserPlanController::class, 'index'])->name('plans.index');

    Route::get('/payments/billing-period', [PaymentController::class, 'billingPeriod'])->name('payments.billing-period');
    Route::get('/payments/currencies', [PaymentController::class, 'currencies'])->name('payments.currencies');
    Route::get('/payments/payment-status', [PaymentController::class, 'currencies'])->name('payments.payment_status');
    Route::get('/payments/methods', [PaymentController::class, 'methods'])->name('payments.methods');
});