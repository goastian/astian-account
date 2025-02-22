<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\Scopes;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Laravel\Passport\Exceptions\MissingScopeException;
use Laravel\Passport\Exceptions\AuthenticationException;
use Laravel\Passport\Http\Middleware\CheckScopes as middleware;

class CheckScopes extends middleware
{
    use Scopes;

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$scopes
     * @return \Illuminate\Http\Response
     *
     * @throws \Laravel\Passport\Exceptions\AuthenticationException|\Laravel\Passport\Exceptions\MissingScopeException
     */
    public function handle($request, $next, ...$scopes)
    {
        if (!$request->user() || !$request->user()->token()) {
            throw new AuthenticationException;
        }

        //Check the api key
        $apiKey = $request->user()->token();
        if (isset($apiKey->id)) {

            if (empty(array_diff($scopes, $apiKey->scopes))) {
                return $next($request);
            }

            throw new ReportError("You do not have the necessary permissions", 403);
        }

        //admin users
        if (auth()->user()->isAdmin()) {
            return $next($request);
        }

        //Verification for non-admin users
        if (auth()->check()) {

            $userScopes = $this->scopes()->pluck('id')->toArray();
            if (!empty($userScopes) && empty(array_diff($scopes, $userScopes))) {
                return $next($request);
            }

            throw new ReportError("You do not have the necessary permissions", 403);
        }

        return $next($request);
    }
}
