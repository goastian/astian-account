<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
        $except = ['send.verification.email', 'check.account', 'verify.account'];

        if (auth()->check() && !in_array(Route::currentRouteName(), $except) && !auth()->user()->verified_at) {

            if ($request->wantsJson()) {
                return response()->json(['message' => __("Your Account is unverified")]);
            }

            return redirect()->route('check.account');
        }

        return $next($request);
    }
}
