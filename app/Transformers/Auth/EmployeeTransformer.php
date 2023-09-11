<?php

namespace App\Transformers\Auth;

use App\Assets\Asset;
use League\Fractal\TransformerAbstract;

class EmployeeTransformer extends TransformerAbstract
{
    use Asset;
    
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
    public function transform($user)
    {
        return [
            'id' => $user->id,
            'nombre' => $user->name,
            'apellido' => $user->last_name,
            'correo_electronico' => $user->email,
            'documento' => $user->document_type,
            'numero' => $user->document_number,
            'pais' => $user->country,
            'departamento' => $user->department,
            'direccion' => $user->address,
            'telefono' => $user->phone,
            'registrado' => $this->format_date($user->created_at),
            'actualizado' => $this->format_date($user->updated_at),
            'inactivo' => $this->format_date($user->deleted_at),
            'links' =>[
                'parent' => route('users.index'),
                'store' => route('users.store'),
                'show' => route('users.show', ['user' => $user->id]), 
                'update' => route('users.update', ['user' => $user->id]), 
                'disable' => route('users.disable', ['user' => $user->id]),
                'enable' => route('users.enable', ['id' => $user->id]),
                'roles' => route('users.roles.index',['user' => $user->id]),
            ]
        ];
    }

    public static function transformRequest($index)
    {
        $attributes = [ 
            'nombre' => 'name',
            'apellido' => 'last_name',
            'correo_electronico' => 'email',
            'contraseña' => 'password',
            'documento' => 'document_type',
            'numero' => 'document_number',
            'pais' => 'country',
            'departamento' => 'department',
            'direccion' => 'address',
            'telefono' => 'phone',
            'acceso' => 'role', 
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }


    public static function transformResponse($index)
    {
        $attributes = [
            'name' => 'nombre',
            'last_name' => 'apellido',
            'email' => 'correo_electronico',
            'password' => 'contraseña',
            'document_type' => 'documento',
            'document_number' => 'numero',
            'country' => 'pais',
            'department' => 'departamento',
            'address' => 'direccion',
            'phone' => 'telefono',
            'role' => 'acceso'
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }


    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'id' => 'id',
            'nombre' => 'name',
            'apellido' => 'last_name',
            'correo_electronico' => 'email',
            'documento' => 'document_type',
            'numero' => 'document_number',
            'pais' => 'country',
            'departamento' => 'department',
            'direccion' => 'address',
            'telefono' => 'phone',
            'registrado' => 'created_at',
            'actualizado' => 'updated_at',
            'inactivo' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
