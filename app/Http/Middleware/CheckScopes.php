<?php

namespace App\Http\Middleware;
  
use Laravel\Passport\Client;
use App\Repositories\Traits\Scopes;
use Elyerr\ApiResponse\Exceptions\ReportError;
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
        // Retrieve token to the  request
        $token = $request->user()->token();

        // Retrieve the client to the token
        $client = Client::find($token->client_id);

        // Checking Authentication
        if (!$request->user() || !$token) {
            throw new AuthenticationException;
        }

        // Use personal access token like a api key
        if ($client->personal_access_client) {

            if (empty(array_diff($scopes, $token->scopes))) {
                return $next($request);
            }

            throw new ReportError("You do not have the necessary permissions", 403);
        }

        // Verify the admin user and add top level
        if (auth()->user()->isAdmin()) {
            return $next($request);
        }

        /**
         * Verification for non-admin users
         */

        // Retrieve the owner scopes
        $userScopes = $this->scopes()->pluck('id')->toArray();

        // Checking the incoming scopes exist in the user scopes
        if (!empty($userScopes) && empty(array_diff($scopes, $userScopes))) {
            return $next($request);
        }

        throw new ReportError("You do not have the necessary permissions", 403);

    }
}
