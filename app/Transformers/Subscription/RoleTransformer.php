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
            'system' => $role->system, 
            'links' => [
                'index' => route('roles.index'),
                'store' => route('roles.store'),
                'show' => route('roles.update', ['role' => $role->id]),
                'update' => route('roles.update', ['role' => $role->id]),
                'destroy' => route('roles.destroy', ['role' => $role->id]),
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
