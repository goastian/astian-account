<?php
namespace App\Traits;

use App\Models\User\UserScope;
use Laravel\Passport\Scope;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription\Scope as ModelScope;

trait Scopes
{
    /**
     * Return the available scope for the user
     * @return array|\Illuminate\Database\Eloquent\Collection<int, Scope>|\Illuminate\Support\Collection<int, Scope>
     */
    public function scopes()
    {

        $query = ModelScope::query();
        $query->where('active', true)->where('api_key', true)->with('role');

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
