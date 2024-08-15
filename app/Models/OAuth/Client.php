<?php

namespace App\Models\OAuth;

use App\Transformers\OAuth\ClientAdminTransformer;
use Elyerr\ApiResponse\Assets\Timestamps;
use Laravel\Passport\Client as PassportClient;

class Client extends PassportClient
{
    use Timestamps;

    public $table = "oauth_clients";

    public $transformer = ClientAdminTransformer::class;
}
