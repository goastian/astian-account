<?php

namespace App\Http\Middleware;

use App\Http\Controllers\OAuth\Scopes;
use Closure;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;
use Laravel\Passport\Exceptions\AuthenticationException;
use Laravel\Passport\Exceptions\MissingScopeException;
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

        if ($request->user()->isAdmin()) {
            return $next($request);
        }

        //sesion sin token
        if (!$request->header('Authorization')) {
            $can_access = [];
            foreach ($scopes as $scope) {
                array_push($can_access, collect($this->scopes())->contains('id', $scope));
            }

            return in_array(true, $can_access) ?
            $next($request) :
            throw new ReportError('No cuenta con los permisos necesarios', 403);
        }

        foreach ($scopes as $scope) {
            if ($request->user()->tokenCan($scope)) {
                return $next($request);
            }
        }

        throw new MissingScopeException($scopes);
    }
}
