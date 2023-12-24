<?php

namespace App\Http\Middleware;

use Closure;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;

class DenyGrantType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $grant_type = ['authorization_code', 'refresh_token'];

        if (in_array($request->grant_type, $grant_type)) {

            return $next($request);
        }

        throw new ReportError("grant type denied", 422);
    }
}
