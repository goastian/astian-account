<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTerms
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
        if ($request->user() && !$request->user()->accept_terms) {
           return redirect()->route('check.terms');
        }

        return $next($request);
    }
}
