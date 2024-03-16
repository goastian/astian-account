<?php
namespace App\Guard;

use Illuminate\Cookie\CookieValuePrefix;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Laravel\Passport\Guards\TokenGuard as GuardsTokenGuard;

final class TokenGuard extends GuardsTokenGuard
{

    /**
     * Get the CSRF token from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function getTokenFromRequest($request)
    {
        $token = $request->header('X-CSRF-TOKEN');

        if (!$token && $header = $request->cookie(config('session.xcsrf-token'))) {
            $token = CookieValuePrefix::remove($this->encrypter->decrypt($header, static::serialized()));
        }

        return $token;
    }


    /**
     * Determine if the cookie contents should be serialized.
     *
     * @return bool
     */
    public static function serialized()
    {
        return EncryptCookies::serialized(config('session.xcsrf-token'));
    }
}
