<?php
namespace App\Transformers\User;

use App\Models\User\User;
use App\Traits\Scopes;
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
        ];
    }
}
