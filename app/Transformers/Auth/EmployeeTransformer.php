<?php

namespace App\Transformers\Auth;

use League\Fractal\TransformerAbstract;

class EmployeeTransformer extends TransformerAbstract
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
    public function transform($user)
    {
        return [
            'identificador' => $user->id,
            'nombre' => $user->name,
            'apellido' => $user->last_name,
            'correo_electronico' => $user->email,
            'documento' => $user->document_type,
            'numero' => $user->document_number,
            'pais' => $user->country,
            'departamento' => $user->department,
            'direccion' => $user->address,
            'registrado' => $user->created_at,
            'actualizado' => $user->updated_at,
            'inactivo' => $user->deleted_at,
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
            'role' => 'acceso'
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }


    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'identificador' => 'id',
            'nombre' => 'name',
            'apellido' => 'last_name',
            'correo_electronico' => 'email',
            'documento' => 'document_type',
            'numero' => 'document_number',
            'pais' => 'country',
            'departamento' => 'department',
            'direccion' => 'address',
            'registrado' => 'created_at',
            'actualizado' => 'updated_at',
            'inactivo' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
