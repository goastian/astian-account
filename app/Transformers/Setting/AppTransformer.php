<?php

namespace App\Transformers\Setting;

use League\Fractal\TransformerAbstract;

class AppTransformer extends TransformerAbstract
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
    public function transform($app)
    {
        return [
            'id' => $app->id,
            'name' => $app->name,
            'url' => $app->url,
            'icon' => $app->icon,
            'description' => $app->description,
            'created_at' => $app->created_at,
            'updated_at' => $app->updated_at,
            'links' => [
                'parent' => route('apps.index'),
                'store' => route('apps.store'),
                'show' => route('apps.show', ['app' => $app->id]),
                'update' => route('apps.update', ['app' => $app->id]),
                'destroy' => route('apps.destroy', ['app' => $app->id]),
            ],

        ];
    }

    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'id' => 'id',
            'name' => 'name',
            'url' => 'url',
            'icon' => 'icon',
            'description' => 'description',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
