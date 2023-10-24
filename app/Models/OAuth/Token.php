<?php

namespace App\Models\OAuth;

use Elyerr\ApiResponse\Assets\Timestamps;
use Laravel\Passport\Token as PassportToken;

class Token extends PassportToken
{ 
    use Timestamps;
}
