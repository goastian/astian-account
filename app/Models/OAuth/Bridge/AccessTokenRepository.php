<?php
namespace App\Models\OAuth\Bridge;

use DateTime;
use Laravel\Passport\Client;
use Laravel\Passport\Events\AccessTokenCreated;
use Laravel\Passport\Bridge\AccessTokenRepository as Model;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;

class AccessTokenRepository extends Model
{
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
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

        // Only attach scopes if the client is a Personal Access Client
        $client = Client::find($clientId);

        // Checking the client exists and is a personal access token
        if ($client && $client->personal_access_client) {
            $data['scopes'] = $this->scopesToArray($accessTokenEntity->getScopes());
        } else {
            $data['scopes'] = [];
        }

        // Create token 
        $this->tokenRepository->create($data);

        // Dispatch token
        $this->events->dispatch(new AccessTokenCreated(
            $accessTokenEntity->getIdentifier(),
            $accessTokenEntity->getUserIdentifier(),
            $accessTokenEntity->getClient()->getIdentifier()
        ));
    }
}
