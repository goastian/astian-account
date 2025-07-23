<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class RedirectIfAuthenticated
{

    /**
     * Summary of handle
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param array $guards
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirectToHome();
            }
        }

        return $next($request);
    }
}
