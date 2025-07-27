<?php
namespace App\Repositories\Traits;

use App\Support\CacheKeys;
use Laravel\Passport\Scope;
use App\Models\User\UserScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Subscription\Scope as ModelScope;

trait Scopes
{
    /**
     * Retrieve all scopes accessible by the authenticated user.
     *
     * This method returns the list of scopes that the current user is allowed to use,
     * optionally filtering them to include only those marked as usable with API keys.
     *
     * - If $api_key is true, only scopes specifically intended for API key usage will be returned.
     * - If the authenticated user is an admin, all active scopes (optionally filtered by API key) are returned.
     * - For non-admin users, only scopes explicitly assigned to the user are returned, and must be active,
     *   not expired, and either public or directly linked to the user.
     *
     * @param bool $api_key Whether to limit the results to API key-compatible scopes.
     * @return \Illuminate\Support\Collection<int, \Laravel\Passport\Scope>
     */
    public function scopes($api_key = true)
    {
        $user = Auth::user();
        $cacheKey = CacheKeys::userScopes($user->id);

        $query = ModelScope::query();
        $query->where('active', true)->with('role');

        if ($api_key) {
            $cacheKey = CacheKeys::userScopesApiKey($user->id);
            $query->where('api_key', true);
        }

        return Cache::remember(
            $cacheKey,
            now()->addDays(intval(config('cache.expires', 90))),
            function () use ($user, $query) {

                if ($user->isAdmin()) {
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
        );
    }
}
