<?php

namespace App\Models\OAuth;

use App\Transformers\OAuth\ClientAdminTransformer; 
use Laravel\Passport\Client as PassportClient;

class Client extends PassportClient
{

    public $table = "oauth_clients";

    public $transformer = ClientAdminTransformer::class;
}
