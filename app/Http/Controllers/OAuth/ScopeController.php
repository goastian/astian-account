<?php

namespace App\Http\Controllers\OAuth;

use App\Models\User\Role;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Http\Controllers\ScopeController as ControllersScopeController;
use Laravel\Passport\Scope;

class ScopeController extends ControllersScopeController
{
    /**
     * Get all of the available scopes for the application.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        if (Auth::user()->isAdmin()) {
            return collect(Role::all())->map(function ($role) {
                return new Scope($role->name, $role->description);
            })->values();
        }

        return collect(Auth::user()->roles()->get())->map(function ($role) {
            return new Scope($role->name, $role->description);
        })->values();
    }
}
