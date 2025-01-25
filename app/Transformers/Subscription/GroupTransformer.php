<?php
namespace App\Transformers\Subscription;


use League\Fractal\TransformerAbstract;

class GroupTransformer extends TransformerAbstract
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
    public function transform($group)
    {
        return [
            'id' => $group->id,
            'name' => $group->name,
            'slug' => $group->slug,
            'description' => $group->description,
            'system' => $group->system, 
            'links' => [
                'index' => route('groups.index'),
                'store' => route('groups.store'),
                'show' => route('groups.show', ['group' => $group->id]),
                'update' => route('groups.update', ['group' => $group->id]),
                'destroy' => route('groups.destroy', ['group' => $group->id]),
            ],

        ];
    }

    /**
     * Retrieve the all keys available for this model
     * @param mixed $index
     * @return string|null
     */
    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'id' => 'id',
            'name' => 'name',
            'slug' => 'slug',
            'description' => 'description',
            'system' => 'system',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
