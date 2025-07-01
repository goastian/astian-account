<?php

namespace App\Models\OAuth;
 
use Laravel\Passport\Client as PassportClient;
use App\Transformers\OAuth\ClientAdminTransformer;

class Client extends PassportClient
{
    public $table = "oauth_clients";
    
    public $transformer = ClientAdminTransformer::class;
}
