<?php

namespace App\Http\Middleware;

use Closure;
use App\Support\CacheKeys;
use Illuminate\Http\Request;
use App\Models\User\UserScope;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CheckUserScopes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            $scopes = Cache::remember(
                CacheKeys::userScopeList($user->id),
                now()->addDays(intval(config('cache.expires', 90))),
                function () use ($user) {

                    $scopes = UserScope::where('user_id', $user->id)
                        ->where(function ($query) {
                            $query->whereNull('end_date')
                                ->orWhere('end_date', '>', now());
                        });

                    return $scopes->get();
                }
            );

            $count = count($scopes->where('end_date', '<=', now()));

            if ($count) {
                Cache::forget(CacheKeys::userScopes($user->id));
                Cache::forget(CacheKeys::userGroups($user->id));
                Cache::forget(CacheKeys::userAdmin($user->id));
                Cache::forget(CacheKeys::userScopesApiKey($user->id));
                Cache::forget(CacheKeys::userScopeList($user->id));
            }
        }

        return $next($request);
    }
}
