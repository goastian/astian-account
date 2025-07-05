<?php
namespace App\Models\OAuth\Bridge;

use DateTime; 
use Laravel\Passport\Passport;
use Laravel\Passport\Events\AccessTokenCreated; 
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;

class AccessTokenRepository extends \OpenIDConnect\Repositories\AccessTokenRepository
{

    /**
     * {@inheritdoc}
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity): void
    {
        // Retrieve the client id of the token 
        $clientId = $accessTokenEntity->getClient()->getIdentifier();

        // Generate data to create token
        $data = [
            'id' => $accessTokenEntity->getIdentifier(),
            'user_id' => $accessTokenEntity->getUserIdentifier(),
            'client_id' => $clientId,
            'revoked' => false,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
            'expires_at' => $accessTokenEntity->getExpiryDateTime(),
        ];
 
        // Checking api key only personal access token
        if (in_array('personal_access', $accessTokenEntity->getClient()->grantTypes)) {
            $data['scopes'] = $accessTokenEntity->getScopes();
        } else {
            $data['scopes'] = [];
        }

        Passport::token()->forceFill($data)->save();

        // Dispatch token
       /* $this->events->dispatch(new AccessTokenCreated(
            $accessTokenEntity->getIdentifier(),
            $accessTokenEntity->getUserIdentifier(),
            $accessTokenEntity->getClient()->getIdentifier()
        ));*/
    }
}
