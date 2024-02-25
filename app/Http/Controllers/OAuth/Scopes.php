<?php

namespace App\Http\Controllers\OAuth;

use App\Models\OAuth\Scope;
use App\Models\User\Role;
use Illuminate\Support\Facades\Auth;

trait Scopes
{
    public function scopes()
    {
        $roles = Role::all();
        $scopes = array();

        /**
         * All scopes for admin users
         */
        if (Auth::user()->isAdmin()) {
            return collect($roles)->map(function ($role) {
                return new Scope($role->name, $role->description, $role->public);
            })->values();
        }

        /**
         * Scopes for no admin users
         */
        foreach (Auth::user()->roles()->get() as $scope) {
            array_push($scopes, [
                'id' => $scope->name,
                'description' => $scope->description,
                'public' => $scope->public,
            ]);
        }

        /**
         * Adding public roles for no admin users
         */
        foreach ($roles as $role) {
            if ($role->public) {
                array_push($scopes, [
                    'id' => $role->name,
                    'description' => $role->description,
                    'public' => $role->public,
                ]);
            }
        }

        /**
         *
         */
        return collect($scopes)->map(function ($scope) {
            return new Scope($scope['id'], $scope['description'], $scope['public']);
        })->values();
    }
}
