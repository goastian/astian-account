<?php

namespace App\Http\Middleware;

use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        $this->denyRedirectForGateways($request);

        if (!$request->expectsJson()) {

            $params = $request->all();

            return route('login', $params);
        }
    }

    /**
     * Deny redirection for Gateways
     * @param mixed $request
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return void
     */
    protected function denyRedirectForGateways($request)
    {
        $URI = $_SERVER['REQUEST_URI'];

        if (strpos($URI, 'gateway')) {

            throw new ReportError(__("Unauthorized"), 401);
        }
    }
}
