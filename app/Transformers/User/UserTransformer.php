<?php
namespace App\Transformers\User;

use App\Models\User\User; 
use App\Repositories\Traits\Scopes;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    use Asset, Scopes;

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
    public function transform(User $user)
    {

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
            'commission_rate' => $user->partner ? $user->partner->commission_rate : 0,
            'full_phone' => $user->dial_code . " " . $user->phone,
            'm2fa' => $user->m2fa,
            'verify_email' => $user->verified_at ? true : false,
            'verified' => $this->format_date($user->verified_at),
            'created' => $this->format_date($user->created_at),
            'last_connected' => $this->format_date($user->last_connected),
            'updated' => $this->format_date($user->updated_at),
            'disabled' => $this->format_date($user->deleted_at),
            'stripe_customer_id' => $user->stripe_customer_id,
            'links' => [
                'index' => route('admin.users.index'),
                'store' => route('admin.users.store'),
                'show' => route('admin.users.show', ['user' => $user->id]),
                'update' => route('admin.users.update', ['user' => $user->id]),
                'disable' => route('admin.users.disable', ['user' => $user->id]),
                'enable' => route('admin.users.enable', ['id' => $user->id]),
                'scopes' => route('admin.users.scopes.index', ['user' => $user->id]),
                'groups' => route('admin.users.groups.index', ['user' => $user->id]),
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
