<?php

namespace App\Repositories\OAuth;

use App\Models\OAuth\Token;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Auth\Authenticatable;

class TokenRepository
{

    /**
     * Construct
     * @param \App\Repositories\OAuth\Token $token
     */
    public function __construct(private Token $token)
    {
    }

    /**
     * Get a token by the given user ID and token ID.
     * @param  \Laravel\Passport\Contracts\OAuthenticatable  $user
     */
    public function findForUser(string $id, Authenticatable $user): ?Token
    {
        $token = $this->token->where('user_id', $user->id)
            ->whereHas('client', function ($query) {
                $query->whereJsonContains('grant_types', 'personal_access');
            })->where('revoked', false)
            ->where('expires_at', '>', Date::now())
            ->find($id);

        return $token;
    }

    /**
     *  Get the token instances for the given user ID.
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     */
    public function forUser(Authenticatable $user): Builder
    {
        $query = $this->token->query();

        $query->where('user_id', $user->id)
            ->whereHas('client', function ($query) {
                $query->whereJsonContains('grant_types', 'personal_access');
            })->where('revoked', false)
            ->where('expires_at', '>', Date::now());

        return $query;
    }
}
