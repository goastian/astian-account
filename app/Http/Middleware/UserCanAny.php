<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use App\Repositories\Traits\Scopes;
use Symfony\Component\HttpFoundation\Response;

class UserCanAny
{
    use Scopes;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$scopes): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->isAdmin()) {
            return $next($request);
        }

        $userScopes = $this->scopes(false)->pluck('id') ?? [];

        if (count($userScopes) && array_intersect($userScopes->toArray(), $scopes)) {
            return $next($request);
        }

        return redirect()->route('users.dashboard');
    }
}
