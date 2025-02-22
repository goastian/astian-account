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
    public function transform($data)
    {
        return [
            'id' => $data->id,
            'name' => $data->name,
            'slug' => $data->slug,
            'description' => $data->description,
            'system' => $data->system,
            'created' => $this->format_date($data->created_at),
            'updated' => $this->format_date($data->updated_at),
            'links' => [
                'parent' => route('admin.broadcasts.index'),
                'store' => route('admin.broadcasts.store'),
                'destroy' => route('admin.broadcasts.destroy', ['broadcast' => $data->id]),
            ],
        ];
    }


    /**
     * Retrieve the original attributes
     * @param mixed $index
     * @return string|null
     */
    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'id' => 'id',
            'name' => 'channel',
            'description' => 'description',
            'system' => 'system',
            'created' => 'created_at',
            'updated' => 'updated_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
