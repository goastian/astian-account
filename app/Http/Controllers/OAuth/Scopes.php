<?php

namespace App\Http\Controllers\OAuth;

use App\Models\User\Role;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Scope;

trait Scopes
{
    public function scopes()
    {
        $roles = Role::all();
        $userRoles = Auth::user()->roles()->get();

        if (Auth::user()->isAdmin()) {
            return collect($roles)->map(function ($role) {
                return new Scope($role->name, $role->description);
            })->values();
        }

        $scopes = array();

        foreach ($roles as $role) {
            foreach ($userRoles as $scope) {
                if (str_starts_with($role->name, $scope->name)) {
                    array_push($scopes, [
                        'id' => $role->name,
                        'description' => $role->description,
                    ]);
                }
            }
        }

        return collect($scopes)->map(function ($scope) {
            return new Scope($scope['id'], $scope['description']);
        })->values();
    }
}
