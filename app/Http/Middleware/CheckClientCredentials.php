<?php

namespace App\Http\Middleware;

use App\Http\Controllers\OAuth\Scopes;
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
        if (in_array('*', $token->scopes)) {
            return;
        }

        if (request()->user()->isAdmin()) {
            return;
        }
        //session sin token
        if (!request()->header('Authorization')) {
            $can_access = [];
            foreach ($scopes as $scope) {
                array_push($can_access, collect($this->scopes())->contains('id', $scope));
            }

            return in_array(true, $can_access) ?:
            throw new ReportError('No cuenta con los permisos necesarios', 403);
        }

        foreach ($scopes as $scope) {
            if ($token->cant($scope)) {
                throw new MissingScopeException($scope);
            }
        }
    }

}
