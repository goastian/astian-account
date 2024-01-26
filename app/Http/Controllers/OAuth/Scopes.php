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

        if (Auth::user()->isAdmin()) {
            return collect($roles)->map(function ($role) {
                return new Scope($role->name, $role->description);
            })->values();
        }

        $scopes = array();
        //roles asignado
        foreach (Auth::user()->roles()->get() as $scope) {
            array_push($scopes, [
                'id' => $scope->name,
                'description' => $scope->description,
            ]);
        }

        //roles publicos
        foreach ($roles as $role) {
            if ($role->public) {
                array_push($scopes, [
                    'id' => $role->name,
                    'description' => $role->description,
                ]);
            }
        }

        return collect($scopes)->map(function ($scope) {
            return new Scope($scope['id'], $scope['description']);
        })->values();
    }
}
