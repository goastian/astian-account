<?php

namespace App\Transformers\Broadcast;

use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class BroadcastTransformer extends TransformerAbstract
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
    public function transform($broadcast)
    {
        return [
            'id' => $broadcast->id,
            'channel' => $broadcast->channel,
            'description' => $broadcast->description,
            'created' => $this->format_date($broadcast->created_at),
            'updated' => $this->format_date($broadcast->updated_at),
            'links' => [
                'parent' => route('broadcasts.index'),
                'store' => route('broadcasts.store'),
                'destroy' => route('broadcasts.destroy', ['broadcast' => $broadcast->id]),
            ],
        ];
    }

    public static function transformRequest($index)
    {
        $attribute = [
            'channel' => 'channel',
            'description' => 'description',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformResponse($index)
    {
        $attribute = [
            'channel' => 'channel',
            'description' => 'description',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'id' => 'id',
            'channel' => 'channel',
            'description' => 'description',
            'creado' => 'created_at',
            'actualizado' => 'updated_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
