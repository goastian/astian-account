<?php
namespace App\Transformers\User;

use App\Models\Subscription\Group;
use League\Fractal\TransformerAbstract;

class UserGroupTransformer extends TransformerAbstract
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
                'assign' => route('admin.users.groups.assign', ['user' => $group->pivot->user_id]),
                'revoke' => route('admin.users.groups.revoke', ['user' => $group->pivot->user_id, 'group' => $group->id])
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
