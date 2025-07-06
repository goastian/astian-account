<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Public\PaymentController;
use App\Http\Controllers\Api\Public\CountriesController;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use App\Http\Controllers\Api\OAuth\PassportConnectController;


/**
 * Gateways to grant access
 */
Route::group(
    [
        'prefix' => 'gateway',
        'as' => 'gateway.',
        'middleware' => ['throttle:gateway']
    ],
    function () {

        Route::get('/check-authentication', [PassportConnectController::class, 'check_authentication'])->name('basic_auth');
        Route::get('/check-scope', [PassportConnectController::class, 'check_scope_any'])->name('auth.scope.any');
        Route::get('/check-scopes', [PassportConnectController::class, 'check_scope_all'])->name('auth.scope.all');
        Route::get('/check-client-credentials', [PassportConnectController::class, 'check_client_credentials'])->name('client.credentials');
        Route::get('/token-can', [PassportConnectController::class, 'user_can'])->name('user.can');
        Route::get('/user', [PassportConnectController::class, 'authenticated'])->name('user.info');
        Route::get('/access', [PassportConnectController::class, 'access'])->name('user.scopes');
        Route::post('/logout', [PassportConnectController::class, 'revokeAuthorization'])->name('logout');
    }
);

/**
 * Oauth Routes to get credentials
 */
Route::group([
    'prefix' => 'oauth',
    'as' => 'oauth.',
    'middleware' => ['throttle:passport-token']
], function () {
    Route::post('/token', [AccessTokenController::class, 'issueToken'])
        ->name('passport.token');
});

Route::group([
    'prefix' => 'public',
    'as' => 'public.',
    'middleware' => ['throttle:default']
], function () {
    Route::resource('/countries', CountriesController::class)->only('index');
    Route::get('/payments/billing-period', [PaymentController::class, 'billingPeriod'])->name('payments.billing-period');
    Route::get('/payments/currencies', [PaymentController::class, 'currencies'])->name('payments.currencies');
    Route::get('/payments/methods', [PaymentController::class, 'methods'])->name('payments.methods');
    Route::get('/services/list', [PaymentController::class, 'services'])->name('services.services');
});