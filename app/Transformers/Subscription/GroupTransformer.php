<?php
namespace App\Transformers\Subscription;


use App\Models\Subscription\Group;
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
    public function transform(Group $group)
    {
        return [
            'id' => $group->id,
            'name' => $group->name,
            'slug' => $group->slug,
            'description' => $group->description,
            'system' => $group->system ? true : false,
            'links' => [
                'index' => route('admin.groups.index'),
                'store' => route('admin.groups.store'),
                'show' => route('admin.groups.show', ['group' => $group->id]),
                'update' => route('admin.groups.update', ['group' => $group->id]),
                'destroy' => route('admin.groups.destroy', ['group' => $group->id]),
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
