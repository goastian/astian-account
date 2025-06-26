<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Public\PaymentController;
use App\Http\Controllers\Api\Public\CountriesController;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use App\Http\Controllers\Api\OAuth\PassportConnectController;


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
});



/**
 * Routes to send notifications
 */
/*
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
});*/


Route::group([
    'prefix' => 'public',
    'as' => 'public.'
], function () {
    Route::resource('/countries', CountriesController::class)->only('index');
    Route::get('/payments/billing-period', [PaymentController::class, 'billingPeriod'])->name('payments.billing-period');
    Route::get('/payments/methods', [PaymentController::class, 'methods'])->name('payments.methods');
    Route::get('/services/list', [PaymentController::class, 'services'])->name('services.services');
});