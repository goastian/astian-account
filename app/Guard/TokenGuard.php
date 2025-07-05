<?php
namespace App\Guard;

use Illuminate\Cookie\CookieValuePrefix;
use Illuminate\Cookie\Middleware\EncryptCookies;
final class TokenGuard extends \Laravel\Passport\Guards\TokenGuard
{

    /**
     * Get the CSRF token from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function getTokenFromRequest(): string
    {
        $token = $this->request->header('X-CSRF-TOKEN');

        if (!$token && $header = $this->request->cookie(config('session.xcsrf-token'))) {
            $token = $token = CookieValuePrefix::remove($this->encrypter->decrypt($header, static::serialized()));
        }

        return $token;
    }


    /**
     * Determine if the cookie contents should be serialized.
     *
     * @return bool
     */
    public static function serialized(): bool
    {
        return EncryptCookies::serialized(config('session.xcsrf-token'));
    }
}
