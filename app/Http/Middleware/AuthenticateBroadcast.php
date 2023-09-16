<?php

namespace App\Http\Middleware;

use App\Models\Sanctum\PersonalAccessToken;
use App\Models\User\Employee;
use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateBroadcast
{
    public function handle($request, Closure $next)
    {
        $token = $this->getAuthorization($request);

        if ($this->user_can_join($token)) {
            return $next($request);
        }

        return $next($request);
    }

    /**
     * Obtiene el token que se envia en la cabecera de la Authorization
     * @param $request
     */
    public function getAuthorization($request)
    {
        return $request->header('Authorization') ?
        explode(' ', $request->header('Authorization'))[1] : null;
    }

    /**
     * obtiene el identificador del usuario a travez del token
     * @param String $token
     */
    public function userID($token)
    {
        $personalToken = PersonalAccessToken::findToken($token);

        return $personalToken ? $personalToken->tokenable_id : null;
    }

    /**
     * verifica que el usuario se autentique
     * @param String $token
     */
    public function user_can_join($token)
    {
        if ($token) {
            $user = Employee::where('id', $this->userID($token))->first();

            if ($user) {
                Auth::login($user);
                return true;
            }
        }

        return false;
    }
}
