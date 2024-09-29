<?php

namespace App\Transformers\Auth;

use League\Fractal\TransformerAbstract;

class EmployeeRoleTransformer extends TransformerAbstract
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
    public function transform($data)
    {

        return [
            'id' => $data->id,
            'scope' => $data->name,
            'links' => [
                'parent' => route('users.roles.index', ['user' => request('user')->id]),
                'store' => route('users.roles.store', ['user' => request('user')->id]),
                'destroy' => route('users.roles.destroy', ['user' => request('user')->id, 'role' => $data->id]),
            ],
        ];

    }

    public static function transformRequest($index)
    {
        $attribute = [
            'scope_id' => 'role_id',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformResponse($index)
    {
        $attribute = [
            'role_id' => 'scope_id',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
