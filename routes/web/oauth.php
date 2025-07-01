<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\OAuth\ClientController;
use Laravel\Passport\Http\Controllers\ScopeController;
use App\Http\Controllers\Web\OAuth\AuthorizationController;
use Laravel\Passport\Http\Controllers\DeviceCodeController;
use Laravel\Passport\Http\Controllers\DeviceUserCodeController;
use Laravel\Passport\Http\Controllers\TransientTokenController;
use App\Http\Controllers\Web\OAuth\PersonalAccessTokenController;
use Laravel\Passport\Http\Controllers\DenyAuthorizationController;
use Laravel\Passport\Http\Controllers\ApproveAuthorizationController;
use Laravel\Passport\Http\Controllers\DeviceAuthorizationController;
use Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController;
use Laravel\Passport\Http\Controllers\DenyDeviceAuthorizationController;
use Laravel\Passport\Http\Controllers\ApproveDeviceAuthorizationController;

Route::group([
    'as' => 'passport.',
    'prefix' => config('passport.path', 'oauth'),
], function () {

    Route::get(
        '/authorize',
        [AuthorizationController::class, 'authorize']
    )->name('authorizations.authorize')->middleware('web');

    Route::get(
        '/device',
        [DeviceUserCodeController::class]
    )->name('device')->middleware('web');

    Route::post(
        '/device/code',
        [DeviceCodeController::class]
    )->name('device.code')->middleware('throttle');


    $guard = config('passport.guard', null);

    Route::middleware(['web', $guard ? 'auth:' . $guard : 'auth'])
        ->group(function () {

            Route::post(
                '/token/refresh',
                [TransientTokenController::class, 'refresh']
            )->name('token.refresh');

            Route::post(
                '/authorize',
                [ApproveAuthorizationController::class, 'approve']
            )->name('authorizations.approve');

            Route::delete(
                '/authorize',
                [DenyAuthorizationController::class, 'deny']
            )->name('authorizations.deny');

            Route::get(
                '/device/authorize',
                [DeviceAuthorizationController::class]
            )->name('device.authorizations.authorize');

            Route::post(
                '/device/authorize',
                [ApproveDeviceAuthorizationController::class]
            )->name('device.authorizations.approve');

            Route::delete(
                '/device/authorize',
                [DenyDeviceAuthorizationController::class]
            )->name('device.authorizations.deny');

            Route::get(
                '/scopes',
                [ScopeController::class, 'all']
            )->name('scopes.index');

            Route::get(
                '/tokens',
                [AuthorizedAccessTokenController::class, 'forUser']
            )->name('tokens.index')->middleware('wants.json');

            Route::delete(
                '/tokens/{token_id}',
                [AuthorizedAccessTokenController::class, 'destroy']
            )->name('tokens.destroy');

            Route::get(
                '/clients',
                [ClientController::class, 'index']
            )->name('clients.index');

            Route::post(
                '/clients',
                [ClientController::class, 'store']
            )->name('clients.store');

            Route::put(
                '/clients/{client_id}',
                [ClientController::class, 'update']
            )->name('clients.update');

            Route::delete(
                '/clients/{client_id}',
                [ClientController::class, 'delete']
            )->name('clients.destroy');

            Route::get(
                '/scopes',
                [PersonalAccessTokenController::class, 'listScopesForApiToken']
            )->name('scopes.index');

            Route::get(
                '/api-keys',
                [PersonalAccessTokenController::class, 'forUser']
            )->name('personal.tokens.index');

            Route::post(
                '/api-keys',
                [PersonalAccessTokenController::class, 'store']
            )->name('personal.tokens.store');

            Route::delete(
                '/api-keys/{token_id}',
                [PersonalAccessTokenController::class, 'destroy']
            )->name('personal.tokens.destroy');

        });
});