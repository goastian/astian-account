<?php

namespace App\Transformers\OAuth;

use League\Fractal\TransformerAbstract;

class PersonalTokenTransformer extends TransformerAbstract
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
    public function transform($data)
    {
        return [
            'id' => $data->id,
            'name' => $data->name,
            'created' => $data->created_at,
            'expires' => $data->expires_at,
            'revoked' => $data->revoked,
            'scopes' => $data->scopes,
            'links' => [
                'index' => route('passport.personal.tokens.index'),
                'store' => route('passport.personal.tokens.store'),
                'destroy' => route('passport.personal.tokens.destroy', ['token_id' => $data->id]),
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
            'id' => 'id',
            'name' => 'name',
            'created' => 'created_at',
            'updated' => 'updated_at'
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
