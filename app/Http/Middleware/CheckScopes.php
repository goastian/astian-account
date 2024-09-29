<?php

namespace App\Http\Middleware;

use App\Http\Controllers\OAuth\Scopes;
use Closure;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;
use Laravel\Passport\Exceptions\AuthenticationException;
use Laravel\Passport\Exceptions\MissingScopeException;
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
        //Verification for non-admin users
        if (!$request->user()->isAdmin()) {
            if (!$request->header('Authorization')) {
                //Check that the scopes are within the scopes assigned to the user.
                foreach ($scopes as $scope) {
                    throw_unless(collect($this->scopes())->contains('id', $scope),
                        new ReportError('no cuenta con los permisos para realizar esta operacion', 403));
                }
            }

            foreach ($scopes as $scope) {
                if (!$request->user()->tokenCan($scope)) {
                    throw new MissingScopeException($scope);
                }
            }
        }

        return $next($request);
    }
}
