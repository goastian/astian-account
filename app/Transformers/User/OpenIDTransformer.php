<?php

namespace App\Transformers\User;

use App\Models\User\User;
use League\Fractal\TransformerAbstract;

class OpenIDTransformer extends TransformerAbstract
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
    public function transform(User $user)
    {
        return [
            "sub" => $user->id,
            "name" => "{$user->name} {$user->last_name}",
            "given_name" => $user->name,
            "family_name" => $user->last_name,
            "email" => $user->email,
            "updated_at" => $user->updated_at,
            "middle_name" => $user->getMiddleName(),
            "birthdate" => $user->birthday,
            //"nickname" => null,
            //"preferred_username" => null,
            "profile" => route('users.dashboard'),
            //"picture" => null,
            //"website" => null,
            //"gender" => null,
            //"zoneinfo" => null,
            //"locale" => null,
            "email_verified" => $user->verified_at ? true : false,
            "phone_number" => $user->dial_code . " " . $user->phone,
            'groups' => $user->myGroups(),
            'id' => $user->id,
            //"phone_number_verified" => null,
            //"address" => $user->address
        ];
    }
}
