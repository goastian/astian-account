<?php

namespace App\Models\OAuth\Bridge;

use App\Repositories\OAuth\Server\Grant\OAuthSessionTokenRepository;
use Laravel\Passport\Passport;
use Laravel\Passport\Bridge\AuthCodeRepository as Model;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;

class AuthCodeRepository extends Model
{

    /**
     * Session token repository
     * @var 
     */
    public $oauth_session_repository;

    public function __construct(OAuthSessionTokenRepository $oAuthSessionTokenRepository)
    {
        $this->oauth_session_repository = $oAuthSessionTokenRepository;
    }
    /**
     * {@inheritdoc}
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity): void
    {
        $identifier = $authCodeEntity->getIdentifier();

        $attributes = [
            'id' => $identifier,
            'user_id' => $authCodeEntity->getUserIdentifier(),
            'client_id' => $authCodeEntity->getClient()->getIdentifier(),
            'scopes' => null,
            'revoked' => false,
            'expires_at' => $authCodeEntity->getExpiryDateTime(),
        ];
       
        Passport::authCode()->forceFill($attributes)->save();

        // Set code to the current session
        $this->oauth_session_repository->create([
            'session_id' => session()->getId(),
            'oauth_auth_code_id' => $identifier
        ]);
    }
}
