<?php

namespace App\Http\Middleware;
  
use Laravel\Passport\Client;
use App\Repositories\Traits\Scopes;
use Elyerr\ApiResponse\Exceptions\ReportError; 
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
        // Retrieve token to the  request
        $token = $request->user()->token();

        // Retrieve the client to the token
        $client = Client::find($token->client_id);

        // Checking authentication
        if (!$request->user() || !$token) {
            throw new AuthenticationException;
        }

        // Use personal access token like a api key
        if ($client->personal_access_client) {

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
        $userScopes = $this->scopes()->pluck('id')->toArray();

        // Intersect the owner scope with incoming scopes
        if (!empty($userScopes) && count(array_intersect($userScopes, $scopes)) > 0) {
            return $next($request);
        }
        
        throw new ReportError("You do not have the necessary permissions", 403);
    }
}
