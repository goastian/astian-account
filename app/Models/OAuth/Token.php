<?php

namespace App\Models\OAuth;

use Elyerr\ApiResponse\Assets\Asset;
use Laravel\Passport\Token as PassportToken;

class Token extends PassportToken
{
    use Asset;

    /**
     * Getter for Created at field
     * @param mixed $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return $this->format_date($value);
    }

    /**
     * Getter for updated at field
     * @param mixed $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        return $this->format_date($value);
    }

    /**
     * Getter for expires at field
     * @param mixed $value
     * @return string
     */
    public function getExpiresAtAttribute($value)
    {
        return $this->format_date($value, 'Y-m-d H:i');
    }

    /**
     * Session
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<OAuthSessionToken, Token>
     */
    public function oauthSessionToken()
    {
        return $this->hasOne(OAuthSessionToken::class, 'oauth_access_token_id', 'id');
    }
}
