<?php

namespace App\Transformers\Auth;

use League\Fractal\TransformerAbstract;

class EmployeeGroupTransformaer extends TransformerAbstract
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
            'description' => $group->description,
            'links' => [
                'parent' => route('users.groups.index', ['user' => request('user')->id]),
                'destroy' => route('users.groups.destroy', ['user' => request('user')->id, 'group' => $group->id]),
            ],
        ];
    }
}
