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
            'scope' => $role->name,
            'description' => $role->description,
            'public' => $role->public,
            'required_payment' => $role->required_payment,
            'links' => [
                'parent' => route('roles.index'),
                'store' => route('roles.store'),
                'show' => route('roles.update', ['role' => $role->id]),
                'update' => route('roles.update', ['role' => $role->id]),
                'destroy' => route('roles.destroy', ['role' => $role->id]),
                'users' => route('roles.users.index', ['role' => $role->id]),
            ],
        ];
    }

    public static function transformRequest($index)
    {
        $attribute = [
            'scope' => 'name',
            'description' => 'description',
            'public' => 'public',
            'required_payment' => 'required_payment',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformResponse($index)
    {
        $attribute = [
            'name' => 'scope',
            'description' => 'description',
            'public' => 'public',
            'required_payment' => 'required_payment',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'id' => 'id',
            'scope' => 'name',
            'description' => 'description',
            'public' => 'public',
            'required_payment' => 'required_payment',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
