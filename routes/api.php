<?php

use App\Enum\EnumType;
use App\Http\Controllers\Auth\AuthorizationController;
use App\Http\Controllers\Broadcasting\BroadcastController;
use App\Http\Controllers\OAuth\CredentialsController;
use App\Http\Controllers\OAuth\PasspotConnectController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserRoleController;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;

//gateways
Route::prefix('gateway')->group(function () {
    Route::get('/check-authentication', [PasspotConnectController::class, 'check_authentication']);
    Route::get('/check-scope', [PasspotConnectController::class, 'check_scope']);
    Route::get('/check-scopes', [PasspotConnectController::class, 'check_scopes']);
    Route::get('/check-client-credentials', [PasspotConnectController::class, 'check_client_credentials']);
    Route::get('/token-can', [PasspotConnectController::class, 'token_can']);
    Route::get('/user', [PasspotConnectController::class, 'auth']);
    Route::post('/logout', [AuthorizationController::class, 'destroy']);
});

Route::prefix('oauth')->group(function () {
    Route::post('/token', [AccessTokenController::class, 'issueToken'])
        ->name('passport.token')
        ->middleware('authorize');

    Route::delete('/credentials/revoke', [CredentialsController::class, "revokeCredentiasl"])
        ->name('passport.revoke-credentials');
});


/**
 * rutas que permiten administrar usuarios dentro del sistema
 */
Route::resource('roles', RoleController::class)->only('index', 'store', 'update', 'destroy');
Route::delete('users/{user}/disable', [UserController::class, 'disable'])->name('users.disable');
Route::get('users/{id}/enable', [UserController::class, 'enable'])->name('users.enable');
Route::resource('users', UserController::class)->except('edit', 'create', 'destroy');
Route::resource('users.roles', UserRoleController::class)->only('index', 'store', 'destroy');
Route::get('document-type', [EnumType::class, 'documento_type']);

/**
 * rutas que permiten administrar los canales de difusion dentro 
 * del sistema a traves de eventos
 */
Route::resource('broadcasts', BroadcastController::class)->only('index','store', 'destroy');