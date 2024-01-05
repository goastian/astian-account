<?php

namespace App\Transformers\Auth;

use App\Models\User\Employee;
use Elyerr\ApiResponse\Assets\Asset;
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
            'correo' => $user->email,
            'pais' => $user->country,
            'ciudad' => $user->city,
            'direccion' => $user->address,
            'nacimiento' => $user->birthday,
            'telefono' => $user->phone,
            'verificado' => $user->verified_at,
            'cliente' => Employee::withTrashed()->find($user->id)->isClient(),
            'm2fa' => $user->m2fa,
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
            'correo' => 'email',
            'password' => 'password',
            'password_confirmation' => 'password_confirmation',
            'pais' => 'country',
            'ciudad' => 'city',
            'direccion' => 'address',
            'telefono' => 'phone',
            'nacimiento' => 'birthday',
            'acceso' => 'role', 
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }


    public static function transformResponse($index)
    {
        $attributes = [
            'name' => 'nombre',
            'last_name' => 'apellido',
            'email' => 'correo',
            'password' => 'password',
            'password_confirmation' => 'password_confirmation',
            'country' => 'pais',
            'city' => 'ciudad',
            'address' => 'direccion',
            'birthday' => 'nacimiento',
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
            'correo' => 'email',
            'pais' => 'country',
            'ciudad' => 'city',
            'direccion' => 'address',
            'telefono' => 'phone', 
            'nacimiento' => 'birthday',
            'verificado' => 'verified_at',
            'm2fa' => 'm2fa',
            'registrado' => 'created_at',
            'actualizado' => 'updated_at',
            'inactivo' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
