<?php

namespace App\Transformers\OAuth;

use League\Fractal\TransformerAbstract;

class ClientAdminTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($client)
    {
        return [
            "id" => $client->id,
            "user_id" => $client->user_id,
            "name" => $client->name,
            "secret" => $client->secret,
            "provider" => $client->provider,
            "redirect" => $client->redirect,
            "personal_access_client" => $client->personal_access_client,
            "password_client" => $client->password_client,
            "revoked" => $client->revoked,
            "created_at" => $client->created_at,
            "updated_at" => $client->updated_at,
        ];
    }

    public static function getOriginalAttributes($index)
    {
        $attributes = [
            "id" => "id",
            "user_id" => "user_id",
            "name" => "name",
            "provider" => "provider",
            "redirect" => "redirect",
            "personal_access_client" => "personal_access_client",
            "password_client" => "password_client",
            "revoked" => "revoked",
            "created_at" => "created_at",
            "updated_at" => "updated_at",
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

}
