<?php

namespace App\Transformers\Role;

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
            'role' => $role->name,
            'descripcion' => $role->description,
            'publico' => $role->public,
            'links' => [
                'parent' => route('roles.index'),
                'store' => route('roles.store'),
                'show' => route('roles.update', ['role' => $role->id]),
                'update' => route('roles.update', ['role' => $role->id]),
                'destroy' => route('roles.destroy', ['role' => $role->id]),
                'users' => route('roles.users.index', ['role' => $role->id])
            ],
        ];
    }

    public static function transformRequest($index)
    {
        $attribute = [
            'role' => 'name',
            'descripcion' => 'description',
            'publico' => 'public',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformResponse($index)
    {
        $attribute = [
            'name' => 'role',
            'description' => 'descripcion',
            'public' => 'publico',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'id' => 'id',
            'nombre' => 'name',
            'descripcion' => 'description',
            'publico' => 'public'
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
