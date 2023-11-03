<?php

namespace App\Models\OAuth;

use Elyerr\ApiResponse\Assets\Timestamps;
use Laravel\Passport\Client as PassportClient;

class Client extends PassportClient
{
    use Timestamps;

}
