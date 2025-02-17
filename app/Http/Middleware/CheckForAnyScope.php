<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\Scopes;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Laravel\Passport\Exceptions\MissingScopeException;
use Laravel\Passport\Exceptions\AuthenticationException;
use Laravel\Passport\Http\Middleware\CheckForAnyScope as middleware;

class CheckForAnyScope extends middleware
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
            if (array_intersect($apiKey->scopes, $scopes)) {
                return $next($request);
            }
            throw new ReportError("You do not have the necessary permissions", 403);
        }

        //Admin users the top level the of application
        if (auth()->user()->isAdmin()) {
            return $next($request);
        }

        //Check no admin user
        if (auth()->check()) {
            $userScopes = $this->scopes()->pluck('id')->toArray();

            if (!empty($userScopes) && count(array_intersect($userScopes, $scopes)) > 0) {
                return $next($request);
            }
            throw new ReportError("You do not have the necessary permissions", 403);
        }

        throw new MissingScopeException($scopes);
    }
}
