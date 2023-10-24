<?php

namespace App\Models\OAuth;
 
use Elyerr\ApiResponse\Assets\Timestamps;
use Laravel\Passport\AuthCode as PassportAuthCode;

class AuthCode extends PassportAuthCode
{
    use Timestamps;
    
}
