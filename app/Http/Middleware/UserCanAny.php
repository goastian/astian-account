<?php

namespace App\Http\Middleware;

use App\Traits\Scopes;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserCanAny
{
    use Scopes;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $scopes): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $scopes = explode(',', $scopes);

        $userScopes = $this->scopes();

        if (!empty($userScopes) && array_intersect($userScopes->pluck('id')->toArray(), $scopes)) {
            return $next($request);
        }

        return redirect()->back()->with('error', __('You do not have the necessary permissions'));
    }
}
