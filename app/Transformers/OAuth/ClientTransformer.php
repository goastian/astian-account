<?php

namespace App\Transformers\OAuth;

use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class ClientTransformer extends TransformerAbstract
{

    use Asset;

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
            "confidential" => $client->secret ? true : false,
            "provider" => $client->provider,
            "redirect" => $client->redirect,
            "personal_access_client" => $client->personal_access_client,
            "password_client" => $client->password_client,
            "revoked" => $client->revoked,
            "created_at" => $this->format_date($client->created_at),
            "updated_at" => $this->format_date($client->updated_at),
            'links' => [
                'index' => route('passport.clients.index'),
                'store' => route('passport.clients.store'),
                'update' => route('passport.clients.update', ['client_id' => $client->id]),
                'destroy' => route('passport.clients.destroy', ['client_id' => $client->id])
            ]
        ];
    }

    /**
     * Transform the original attribute
     * @param mixed $index
     * @return string|null
     */
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
