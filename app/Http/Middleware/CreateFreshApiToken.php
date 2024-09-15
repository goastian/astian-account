<?php

namespace App\Http\Middleware;

use App\Models\OAuth\ApiTokenCookieFactory;
use Laravel\Passport\Http\Middleware\CreateFreshApiToken as ApiToken;

class CreateFreshApiToken extends ApiToken
{
    public function __construct(ApiTokenCookieFactory $apiTokenCookieFactory)
    {
        parent::__construct($apiTokenCookieFactory);
    }
}
