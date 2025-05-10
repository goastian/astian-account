<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\OAuth\ScopeController;

Route::group([
    'as' => 'passport.',
    'prefix' => config('passport.path', 'oauth'),
], function () {

    Route::get('/authorize', [
        'uses' => '\App\Http\Controllers\Web\OAuth\AuthorizationController@authorize',
        'as' => 'authorizations.authorize',
        'middleware' => ['web', 'auth:web'],
    ]);

    $guard = config('passport.guard', null);

    Route::middleware(['web', $guard ? 'auth:' . $guard : 'auth'])->group(function () {

        Route::get('/scopes', [ScopeController::class, 'all'])->name('scopes.index');

        Route::post('/token/refresh', [
            'uses' => '\Laravel\Passport\Http\Controllers\TransientTokenController@refresh',
            'as' => 'token.refresh',
        ]);

        Route::post('/authorize', [
            'uses' => '\Laravel\Passport\Http\Controllers\ApproveAuthorizationController@approve',
            'as' => 'authorizations.approve',
        ]);

        Route::delete('/authorize', [
            'uses' => '\App\Http\Controllers\Web\OAuth\DenyAuthorizationController@deny',
            'as' => 'authorizations.deny'
        ]);

        Route::get('/tokens', [
            'uses' => '\Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController@forUser',
            'as' => 'tokens.index',
            'middleware' => 'wants.json',
        ]);

        Route::delete('/tokens/{token_id}', [
            'uses' => '\Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController@destroy',
            'as' => 'tokens.destroy',
        ]);

        Route::get('/clients', [
            'uses' => '\App\Http\Controllers\Web\OAuth\ClientController@forUser',
            'as' => 'clients.index',
        ]);

        Route::post('/clients', [
            'uses' => '\App\Http\Controllers\Web\OAuth\ClientController@store',
            'as' => 'clients.store',
        ]);

        Route::put('/clients/{client_id}', [
            'uses' => '\App\Http\Controllers\Web\OAuth\ClientController@update',
            'as' => 'clients.update',
        ]);

        Route::delete('/clients/{client_id}', [
            'uses' => '\App\Http\Controllers\Web\OAuth\ClientController@destroy',
            'as' => 'clients.destroy',
        ]);

        Route::get('/api-keys', [
            'uses' => '\App\Http\Controllers\Web\OAuth\PersonalAccessTokenController@forUser',
            'as' => 'personal.tokens.index',
        ]);

        Route::post('/api-keys', [
            'uses' => '\App\Http\Controllers\Web\OAuth\PersonalAccessTokenController@store',
            'as' => 'personal.tokens.store',
        ]);

        Route::delete('/api-keys/{token_id}', [
            'uses' => '\Laravel\Passport\Http\Controllers\PersonalAccessTokenController@destroy',
            'as' => 'personal.tokens.destroy',
        ]);
    });

});
