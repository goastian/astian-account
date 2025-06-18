<?php

namespace App\Http\Middleware;

 
use App\Repositories\Traits\Scopes;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Laravel\Passport\Exceptions\MissingScopeException;
use Laravel\Passport\Http\Middleware\CheckClientCredentials as middleware;

class CheckClientCredentials extends middleware
{
    use Scopes;

    /**
     * Validate token credentials.
     *
     * @param  \Laravel\Passport\Token  $token
     * @param  array  $scopes
     * @return void
     *
     * @throws \Laravel\Passport\Exceptions\MissingScopeException
     */
    protected function validateScopes($token, $scopes)
    {
        //Disable temporarily
        throw new ReportError("You do not have the necessary permissions", 403);
    }

}
