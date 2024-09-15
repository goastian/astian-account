<?php

namespace App\Models\OAuth;

use Illuminate\Support\Carbon;
use Laravel\Passport\ApiTokenCookieFactory as Factory;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Cookie;

class ApiTokenCookieFactory extends Factory
{

    /**
     * Create a new API token cookie.
     *
     * @param  mixed  $userId
     * @param  string  $csrfToken
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    public function make($userId, $csrfToken)
    {
        $config = $this->config->get('session');

        $expiration = Carbon::now()->addMinutes($config['lifetime']);

        return new Cookie(
            Passport::cookie(),
            $this->createToken($userId, $csrfToken, $expiration),
            $expiration,
            $config['path'],
            $config['domain'],
            $config['secure'],
            $config['http_only'],
            false,
            $config['same_site'] ?? null,
            $config['partitioned'] ?? false
        );
    }
}
