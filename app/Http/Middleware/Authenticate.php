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

            $param = $this->sendParamsIfExist($request);

            return route('login', $param);
        }
    }

    /**
     * denegar la redireccion cuando se estableca la coneccion desde un gateway y darles
     * una respuesta 401, para informar que no esta authenticado y necesita crear credenciales
     * @param \Illuminate\Http\Request  $request
     * @return void
     */
    protected function denyRedirectForGatways($request)
    {
        $URI = $_SERVER['REQUEST_URI'];

        if (strpos($URI, 'gateway')) {

            throw new ReportError(__("Unauthorized"), 401);
        }
    }

    /**
     * reenvia todos los datos que vaya en la url al login
     * @param  \Illuminate\Http\Request  $request
     * @return Array
     */
    protected function sendParamsIfExist($request)
    {
        return $request->all();
    }
}
