<?php
namespace App\Transformers\Subscription;


use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract
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
    public function transform($role)
    {
        return [
            'id' => $role->id,
            'name' => $role->name,
            'slug' => $role->slug,
            'description' => $role->description,
            'system' => $role->system ? true : false,
            'links' => [
                'index' => route('admin.roles.index'),
                'store' => route('admin.roles.store'),
                'show' => route('admin.roles.update', ['role' => $role->id]),
                'update' => route('admin.roles.update', ['role' => $role->id]),
                'destroy' => route('admin.roles.destroy', ['role' => $role->id]),
            ],
        ];
    }

    /**
     * Return the original attribute 
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
