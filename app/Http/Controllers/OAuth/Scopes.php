<?php

namespace App\Http\Controllers\OAuth;

use App\Models\User\Role;
use Laravel\Passport\Scope;
use Illuminate\Support\Facades\Auth;

trait Scopes
{

    public function scopes()
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
