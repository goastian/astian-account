<?php

namespace App\Http\Middleware;

use Closure;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;

class VerifyAccount
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
        if ($request->user() and $request->user()->isClient() and !$request->user()->verified_at ) {
            throw new ReportError(__('please, check your email address to activate your account'), 403);
        }
         
        return $next($request);

    }
}
