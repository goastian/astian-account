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
        ];
    }


    public static function transformRequest($index)
    {
        $attribute = [
            'role' => 'role_id'
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformResponse($index)
    {
       $attribute = [
            'role_id' => 'role'
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
