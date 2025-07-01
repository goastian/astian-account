<?php

use App\Http\Controllers\Web\Admin\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Admin\User\UserController;
use App\Http\Controllers\Web\Admin\User\UserGroupController;
use App\Http\Controllers\Web\Admin\User\UserScopeController;
use App\Http\Controllers\Web\Admin\Setting\TerminalController;
use App\Http\Controllers\Web\Admin\OAuth\ClientAdminController;
use App\Http\Controllers\Web\Admin\Subscription\PlanController;
use App\Http\Controllers\Web\Admin\Subscription\RoleController;
use App\Http\Controllers\Web\Admin\Subscription\GroupController;
use App\Http\Controllers\Web\Admin\Subscription\ScopeController;
use App\Http\Controllers\Web\Admin\Subscription\ServiceController;
use App\Http\Controllers\Web\Admin\Broadcasting\BroadcastController;
use App\Http\Controllers\Web\Admin\Subscription\PlanPriceController;
use App\Http\Controllers\Web\Admin\Subscription\PlanScopeController;
use App\Http\Controllers\Web\Admin\Subscription\ServiceScopeController;
use App\Http\Controllers\Web\Admin\Manager\TransactionManagerController;


Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

Route::resource('groups', GroupController::class)->except('edit', 'create');
Route::resource('roles', RoleController::class)->except('create', 'edit');

Route::resource('services', ServiceController::class)->except('create', 'edit');
Route::get('services/{service}/scopes', [ServiceScopeController::class, 'index'])->name('service.scopes.index');
Route::post('services/{service}/scopes', [ServiceScopeController::class, 'assign'])->name('service.scopes.assign');
Route::delete('services/{service}/scopes/{scope}', [ServiceScopeController::class, 'revoke'])->name('services.scopes.revoke');

Route::resource('scopes', ScopeController::class)->only('index');

Route::resource('broadcasts', BroadcastController::class)->only('index', 'store', 'destroy');


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
Route::post('/clients/personal', [ClientAdminController::class, 'createPersonalClient'])->name('clients.personal.store');

Route::resource('/plans', PlanController::class)->except('edit', 'create');
Route::delete('/plans/{plan}/scopes/{scope}', [PlanScopeController::class, 'revoke'])->name('plans.scopes.revoke');
Route::delete('/plans/{plan}/prices/{price}', [PlanPriceController::class, 'destroy'])->name('plans.prices.destroy');

Route::get('/transactions', [TransactionManagerController::class, 'index'])->name('transactions.index');
Route::put('/transactions/{transaction}', [TransactionManagerController::class, 'activate'])->name('transactions.activate');

Route::resource('terminals', TerminalController::class)->only('index', 'store');
