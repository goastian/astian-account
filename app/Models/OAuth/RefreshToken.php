<?php

namespace App\Models\OAuth;

use Elyerr\ApiResponse\Assets\Timestamps;
use Laravel\Passport\RefreshToken as PassportRefreshToken;

class RefreshToken extends PassportRefreshToken
{
    use Timestamps;

}
