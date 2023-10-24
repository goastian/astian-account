<?php

namespace App\Models\OAuth;

use Elyerr\ApiResponse\Assets\Timestamps;
use Laravel\Passport\PersonalAccessClient as PassportPersonalAccessClient;

class PersonalAccessClient extends PassportPersonalAccessClient
{
    use Timestamps;

}
