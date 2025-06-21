<?php
namespace App\Transformers\User;

use App\Models\User\User; 
use App\Repositories\Traits\Scopes;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class AuthTransformer extends TransformerAbstract
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
            'full_phone' => $user->dial_code . " " . $user->phone,
            'm2fa' => $user->m2fa ? true : false,
            'groups' => $user->myGroups(),
            'verify_email' => $user->verified_at ? true : false,
            'verified' => $this->format_date($user->verified_at),
            'created' => $this->format_date($user->created_at),
            'updated' => $this->format_date($user->updated_at),
            'disabled' => $this->format_date($user->deleted_at),
            'links' => [
                'update' => route('users.update'),
                'change_password' => route('users.change.password'),
                'send_verification_email' => route('users.verification.email'),
                'verify_account' => route('users.verify.account'),
                'verified_account' => route('users.verified.account'),
                'check_account' => route('users.check.account'),
                'f2a_send_code' => route('users.2fa.send-code'),
                'f2a_login' => route('users.2fa.login'),
                'f2a_activate' => route('users.2fa.activate'),
                'f2a_authorize' => route('users.2fa.authorize'),
                'subscriptions' => route('users.subscriptions.index'),
                'subscriptions_buy' => route('users.subscriptions.buy'),
                'subscriptions_renew' => route('users.subscriptions.renew')
            ],
        ];
    }
}
