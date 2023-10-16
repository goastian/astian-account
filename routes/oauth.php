<?php

use Illuminate\Support\Facades\Route;

Route::post('/token', [
    'uses' => [AccessTokenController::class,'issueToken'],
    'as' => 'token',
    'middleware' => 'throttle',
]);

Route::get('/authorize', [
    'uses' => [AuthorizationController::class, 'authorize'],
    'as' => 'authorizations.authorize',
    'middleware' => 'web',
]);

$guard = config('passport.guard', null);

Route::middleware(['web', $guard ? 'auth:'.$guard : 'auth'])->group(function () {
    Route::post('/token/refresh', [
        'uses' => [TransientTokenController::class,'refresh'],
        'as' => 'token.refresh',
    ]);

    Route::post('/authorize', [
        'uses' => [ApproveAuthorizationController::class, 'approve'],
        'as' => 'authorizations.approve',
    ]);

    Route::delete('/authorize', [
        'uses' => [DenyAuthorizationController::class,'deny'],
        'as' => 'authorizations.deny',
    ]);

    Route::get('/tokens', [
        'uses' => [AuthorizedAccessTokenController::class, 'forUser'],
        'as' => 'tokens.index',
    ]);

    Route::delete('/tokens/{token_id}', [
        'uses' => [AuthorizedAccessTokenController::class, 'destroy'],
        'as' => 'tokens.destroy',
    ]);

    Route::get('/clients', [
        'uses' => [ClientController::class,'forUser'],
        'as' => 'clients.index',
    ]);

    Route::post('/clients', [
        'uses' => [ClientController::class, 'store'],
        'as' => 'clients.store',
    ]);

    Route::put('/clients/{client_id}', [
        'uses' => [ClientController::class,'update'],
        'as' => 'clients.update',
    ]);

    Route::delete('/clients/{client_id}', [
        'uses' => [ClientController::class,'destroy'],
        'as' => 'clients.destroy',
    ]);

    Route::get('/scopes', [
        'uses' => [ScopeController::class, 'all'],
        'as' => 'scopes.index',
    ]);

    Route::get('/personal-access-tokens', [
        'uses' => [PersonalAccessTokenController::class, 'forUser'],
        'as' => 'personal.tokens.index',
    ]);

    Route::post('/personal-access-tokens', [
        'uses' => [PersonalAccessTokenController::class, 'store'],
        'as' => 'personal.tokens.store',
    ]);

    Route::delete('/personal-access-tokens/{token_id}', [
        'uses' => [PersonalAccessTokenController::class,'destroy'],
        'as' => 'personal.tokens.destroy',
    ]);
});