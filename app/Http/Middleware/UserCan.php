<?php

namespace App\Http\Middleware;

use App\Http\Controllers\OAuth\Scopes;
use Closure;
use Illuminate\Http\Request;

class UserCan
{
    use Scopes;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
         
        return $next($request);
    }
}
