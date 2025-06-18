<?php
namespace App\Repositories\Traits;

use App\Models\User\UserScope;
use Laravel\Passport\Scope;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription\Scope as ModelScope;

trait Scopes
{
    /**
     * Retrieve all scopes available for the authenticated user corresponding to the API key.
     * @param mixed $api_key
     * @return \Illuminate\Database\Eloquent\Collection<int, Scope>|\Illuminate\Support\Collection<int, Scope>
     */
    public function scopes($api_key = true)
    {
        $query = ModelScope::query();
        $query->where('active', true)->with('role');

        if ($api_key) {
            $query->where('api_key', true);
        }

        if (Auth::user()->isAdmin()) {
            return $query->get()
                ->map(fn($scope) => new Scope($scope->gsr_id, $scope->role->description))
                ->values();
        }

        $userScopes = UserScope::where('user_id', auth()->user()->id)
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>', now());
            })->whereHas('scope', function ($query) {
                $query->where('active', true)->orWhere('public', true);
            });

        return $userScopes->get()
            ->map(fn($scope) => new Scope($scope->gsr_id, $scope->scope->role->description))
            ->values();
    }

    /**
     * Retrieve the all scopes available for auth users
     * @return \Illuminate\Database\Eloquent\Collection<int, Scope>|\Illuminate\Support\Collection<int, Scope>
     */
    public function availableScopes()
    {
        $query = ModelScope::query();
        $query->where('active', true)->with('role');

        if (Auth::user()->isAdmin()) {
            return $query->get()
                ->map(fn($scope) => new Scope($scope->gsr_id, $scope->role->description))
                ->values();
        }

        $userScopes = UserScope::where('user_id', auth()->user()->id)
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>', now());
            })->whereHas('scope', function ($query) {
                $query->where('active', true)->orWhere('public', true);
            });

        return $userScopes->get()
            ->map(fn($scope) => new Scope($scope->gsr_id, $scope->scope->role->description))
            ->values();
    }
}
