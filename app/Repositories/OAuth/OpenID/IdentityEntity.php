<?php

namespace App\Repositories\OAuth\OpenID;

use App\Models\User\User; 
class IdentityEntity extends \OpenIDConnect\Entities\IdentityEntity
{
    public function getClaims(array $scopes = []): array
    {

        $user = User::find($this->getIdentifier());
        
        /**
         * For a complete list of default claim sets
         * @see \OpenIDConnect\ClaimExtractor
         */
        return [
            // profile
            'name' => $user->name,
            'nickname' => $user->name, 
            'email' => $user->email,
            'email_verified' => $user->verified_at ? true : false, 
            'phone_number' => $user->phone ? $user->dial_code . " " . $user->phone : null,
            'phone_number_verified' => true, 
            'address' => $user->address,

            // custom
            //'what_he_knows' => 'Nothing!',
        ];
    }
}
