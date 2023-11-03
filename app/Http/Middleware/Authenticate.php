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
        $this->denyRedirectForGatways($request);

        if (!$request->expectsJson()) {
            return route('login', [
                'redirect_uri' => $request->redirect_uri,
                'state' => $request->state,
            ]);
        }
    }

    protected function denyRedirectForGatways($request)
    {
        $URI = $_SERVER['REQUEST_URI'];

        if (strpos($URI, 'gateway')) {

            throw new ReportError("unauthenticated", 401);
        }
    }
}
