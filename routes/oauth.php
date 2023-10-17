<?php

use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\ScopeController;
use Laravel\Passport\Http\Controllers\ClientController;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\Http\Controllers\AuthorizationController;
use Laravel\Passport\Http\Controllers\TransientTokenController;
use Laravel\Passport\Http\Controllers\DenyAuthorizationController;
use Laravel\Passport\Http\Controllers\PersonalAccessTokenController;
use Laravel\Passport\Http\Controllers\ApproveAuthorizationController;
use Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController;

Route::post('/oauth/token', [
    'uses' => [AccessTokenController::class,'issueToken'],
    'as' => 'token',
    'middleware' => 'throttle',
]);

Route::get('/oauth/authorize', [
    'uses' => [AuthorizationController::class, 'authorize'],
    'as' => 'authorizations.authorize',
    'middleware' => 'web',
]);

$guard = config('passport.guard', null);

Route::middleware(['web', $guard ? 'auth:'.$guard : 'auth'])->group(function () {
    Route::post('/oauth/token/refresh', [
        'uses' => [TransientTokenController::class,'refresh'],
        'as' => 'token.refresh',
    ]);

    Route::post('/oauth/authorize', [
        'uses' => [ApproveAuthorizationController::class, 'approve'],
        'as' => 'authorizations.approve',
    ]);

    Route::delete('/oauth/authorize', [
        'uses' => [DenyAuthorizationController::class,'deny'],
        'as' => 'authorizations.deny',
    ]);

    Route::get('/oauth/tokens', [
        'uses' => [AuthorizedAccessTokenController::class, 'forUser'],
        'as' => 'tokens.index',
    ]);

    Route::delete('/oauth/tokens/{token_id}', [
        'uses' => [AuthorizedAccessTokenController::class, 'destroy'],
        'as' => 'tokens.destroy',
    ]);

    Route::get('/oauth/clients', [
        'uses' => [ClientController::class,'forUser'],
        'as' => 'clients.index',
    ]);

    Route::post('/oauth/clients', [
        'uses' => [ClientController::class, 'store'],
        'as' => 'clients.store',
    ]);

    Route::put('/oauth/clients/{client_id}', [
        'uses' => [ClientController::class,'update'],
        'as' => 'clients.update',
    ]);

    Route::delete('/oauth/clients/{client_id}', [
        'uses' => [ClientController::class,'destroy'],
        'as' => 'clients.destroy',
    ]);

    Route::get('/oauth/scopes', [
        'uses' => [ScopeController::class, 'all'],
        'as' => 'scopes.index',
    ]);

    Route::get('/oauth/personal-access-tokens', [
        'uses' => [PersonalAccessTokenController::class, 'forUser'],
        'as' => 'personal.tokens.index',
    ]);

    Route::post('/oauth/personal-access-tokens', [
        'uses' => [PersonalAccessTokenController::class, 'store'],
        'as' => 'personal.tokens.store',
    ]);

    Route::delete('/oauth/personal-access-tokens/{token_id}', [
        'uses' => [PersonalAccessTokenController::class,'destroy'],
        'as' => 'personal.tokens.destroy',
    ]);
});