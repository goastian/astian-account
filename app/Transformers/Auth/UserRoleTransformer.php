<?php

namespace App\Transformers\Auth;

use App\Models\User\Role;
use League\Fractal\TransformerAbstract;

class UserRoleTransformer extends TransformerAbstract
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
        $role = Role::find($data->id);

        return [
            'id' => $role->id,
            'scope' => $role->name,
            'group' => $role->group->name,
            'links' => [
                'parent' => route('users.roles.index', ['user' => request('user')->id]),
                'store' => route('users.roles.store', ['user' => request('user')->id]),
                'destroy' => route('users.roles.destroy', ['user' => request('user')->id, 'role' => $data->id]),
            ],
        ];
    }

    /**
     * Retrieve the keys available for this model
     * @param mixed $index
     * @return string|null
     */
    public static function getOriginalAttributes($index)
    {
        $index = [
            
        ];
    }
}
