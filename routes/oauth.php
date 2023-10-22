<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'as' => 'passport.',
    'prefix' => config('passport.path', 'oauth'),
], function () {

    Route::post('/token', [
        'uses' => '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken',
        'as' => 'token',
        'middleware' => 'throttle',
    ]);

    Route::get('/authorize', [
        'uses' => '\Laravel\Passport\Http\Controllers\AuthorizationController@authorize',
        'as' => 'authorizations.authorize',
        'middleware' => ['web'],
    ]);

    $guard = config('passport.guard', null);

    Route::middleware(['web', $guard ? 'auth:' . $guard : 'auth'])->group(function () {
        Route::post('/token/refresh', [
            'uses' => '\Laravel\Passport\Http\Controllers\TransientTokenController@refresh',
            'as' => 'token.refresh',
        ]);

        Route::post('/authorize', [
            'uses' => '\Laravel\Passport\Http\Controllers\ApproveAuthorizationController@approve',
            'as' => 'authorizations.approve',
        ]);

        Route::delete('/authorize', [
            'uses' => '\Laravel\Passport\Http\Controllers\DenyAuthorizationController@deny',
            'as' => 'authorizations.deny',
        ]);

        Route::get('/tokens', [
            'uses' => '\Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController@forUser',
            'as' => 'tokens.index',
            'middleware' =>'wants.json'
        ]);

        Route::delete('/tokens/{token_id}', [
            'uses' => '\Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController@destroy',
            'as' => 'tokens.destroy',
        ]);

        Route::get('/clients', [
            'uses' => '\Laravel\Passport\Http\Controllers\ClientController@forUser',
            'as' => 'clients.index',
            'middleware' =>'wants.json'
        ]);

        Route::post('/clients', [
            'uses' => '\Laravel\Passport\Http\Controllers\ClientController@store',
            'as' => 'clients.store',
        ]);

        Route::put('/clients/{client_id}', [
            'uses' => '\Laravel\Passport\Http\Controllers\ClientController@update',
            'as' => 'clients.update',
        ]);

        Route::delete('/clients/{client_id}', [
            'uses' => '\Laravel\Passport\Http\Controllers\ClientController@destroy',
            'as' => 'clients.destroy',
        ]);

        Route::get('/scopes', [
            'uses' => '\App\Http\Controllers\OAuth\ScopeController@all',
            'as' => 'scopes.index',
            'middleware' =>'wants.json'
        ]);

        Route::get('/personal-access-tokens', [
            'uses' => '\Laravel\Passport\Http\Controllers\PersonalAccessTokenController@forUser',
            'as' => 'personal.tokens.index',
            'middleware' =>'wants.json'
        ]);

        Route::post('/personal-access-tokens', [
            'uses' => '\Laravel\Passport\Http\Controllers\PersonalAccessTokenController@store',
            'as' => 'personal.tokens.store',
        ]);

        Route::delete('/personal-access-tokens/{token_id}', [
            'uses' => '\Laravel\Passport\Http\Controllers\PersonalAccessTokenController@destroy',
            'as' => 'personal.tokens.destroy',
        ]);
    });

});
