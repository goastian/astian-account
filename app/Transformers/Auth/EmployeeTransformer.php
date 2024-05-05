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
        $user = Employee::withTrashed()->find($user->id);

        return [
            'id' => $user->id,
            'name' => $user->name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'country' => $user->country,
            'city' => $user->city,
            'address' => $user->address,
            'birthday' => $user->birthday,
            'phone' => $user->phone,
            'dial_code' => $user->dial_code,
            'full_phone' => $user->dial_code . " " .$user->phone,
            'm2fa' => $user->m2fa,
            'verified' => $this->format_date($user->verified_at),
            'created' => $this->format_date($user->created_at),
            'updated' => $this->format_date($user->updated_at),
            'disabled' => $this->format_date($user->deleted_at),
            'access' => [
                'client' => $user->isClient(),
                'admin' => $user->isAdmin(),
                'users' => $user->userCan([
                    'account_read',
                    'account_register',
                    'account_update',
                    'account_enable',
                    'account_disable',
                ]),
                'roles' => $user->userCan([
                    'scopes_read',
                    'scopes_register',
                    'scopes_update',
                    'scopes_destroy',
                ]),
                'broadcast' => $user->userCan('broadcast'),
                'notification' => $user->userCan('notify'),
            ],
            'links' => [
                'parent' => route('users.index'),
                'store' => route('users.store'),
                'show' => route('users.show', ['user' => $user->id]),
                'update' => route('users.update', ['user' => $user->id]),
                'disable' => route('users.disable', ['user' => $user->id]),
                'enable' => route('users.enable', ['id' => $user->id]),
                'roles' => route('users.roles.index', ['user' => $user->id]),
            ],
        ];
    }

    public static function transformRequest($index)
    {
        $attributes = [
            'name' => 'name',
            'last_name' => 'last_name',
            'email' => 'email',
            'password' => 'password',
            'password_confirmation' => 'password_confirmation',
            'country' => 'country',
            'city' => 'city',
            'address' => 'address',
            'phone' => 'phone',
            'dial_code' => 'dial_code',
            'birthday' => 'birthday',
            'scope' => 'role',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformResponse($index)
    {
        $attributes = [
            'name' => 'name',
            'last_name' => 'last_name',
            'email' => 'email',
            'password' => 'password',
            'password_confirmation' => 'password_confirmation',
            'country' => 'country',
            'city' => 'city',
            'address' => 'address',
            'birthday' => 'birthday',
            'phone' => 'phone',
            'dial_code' => 'dial_code',
            'role' => 'scope',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'id' => 'id',
            'name' => 'name',
            'last_name' => 'last_name',
            'email' => 'email',
            'country' => 'country',
            'city' => 'city',
            'address' => 'address',
            'phone' => 'phone',
            'dial_code' => 'dial_code',
            'birthday' => 'birthday',
            'verified' => 'verified_at',
            'm2fa' => 'm2fa',
            'created' => 'created_at',
            'updated' => 'updated_at',
            'disabled' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
