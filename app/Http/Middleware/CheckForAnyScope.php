<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use App\Repositories\Traits\Scopes;
use Symfony\Component\HttpFoundation\Response;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Laravel\Passport\Exceptions\AuthenticationException;
use Laravel\Passport\Http\Middleware\CheckTokenForAnyScope as middleware;

class CheckForAnyScope extends middleware
{
    use Scopes;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$scopes): Response
    {
        // Retrieve token to the  request
        $token = $request->user()->token(); 
        // Checking authentication
        if (!$request->user() || !$token) {
            throw new AuthenticationException;
        }

        // Use personal access token like a api key
        if ($token->client->hasGrantType('personal_access')) {

            if (array_intersect($token->scopes, $scopes)) {
                return $next($request);
            }

            throw new ReportError("You do not have the necessary permissions", 403);
        }

        // Check the user is admin set top level access
        if (auth()->user()->isAdmin()) {
            return $next($request);
        }

        /**
         * The other users
         */

        // Retrieve the all scopes available for this users
        $userScopes = $this->scopes(false)->pluck('id')->toArray();

        // Intersect the owner scope with incoming scopes
        if (!empty($userScopes) && count(array_intersect($userScopes, $scopes)) > 0) {
            return $next($request);
        }

        throw new ReportError("You do not have the necessary permissions", 403);
    }
}
