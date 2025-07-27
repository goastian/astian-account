<?php
namespace App\Repositories\OAuth\Server\Grant;

use App\Models\OAuth\Token;
use App\Models\Setting\Session;
use App\Models\OAuth\OAuthSessionToken;


class OAuthSessionTokenRepository
{

    /**
     * 
     * @var App\Models\OAuth\OAuthSessionToken::class
     */
    protected $model;

    public function __construct(OAuthSessionToken $oAuthSessionToken)
    {
        $this->model = $oAuthSessionToken;
    }

    /**
     * Create new session
     * @param array $data
     * @return void
     */
    public function create(array $data)
    {
        $this->model->create($data);
    }

    /**
     * Update oauth session
     * @param string $code_id
     * @param string $token_id
     * @return void
     */
    public function update(string $code_id, string $token_id)
    {
        $this->model->where('oauth_auth_code_id', $code_id)
            ->update(['oauth_access_token_id' => $token_id]);
    }

    /**
     * Find the token session
     * @param string $token_id
     * @return OAuthSessionToken
     */
    public function findToken(string $token_id)
    {
        return $this->model->where('oauth_access_token_id', $token_id)->first();
    }

    /**
     * Retrieve the session token by session id
     * @param string $session_id
     * @return OAuthSessionToken
     */
    public function findSession(string $session_id)
    {
        return $this->model->where('session_id', $session_id)->first();
    }

    /**
     * Revoke and remove all OAuth2 tokens associated with a given session.
     *
     * This method iterates through all token records tied to the specified session ID,
     * revoking both access and refresh tokens (if available) for each session entry.
     * After revocation, it deletes the session's token records from the database,
     * and finally removes the session itself.
     *
     * This is particularly useful for revoking Personal Access Tokens (PATs) linked to a user's session,
     * ensuring full logout and token invalidation across client applications.
     *
     * @param string $session_id The unique session ID for which tokens should be revoked.
     * @return void
     */
    public function destroyTokenSession(string $session_id)
    {
        // Retrieve the current session
        $oauth2_session = $this->model->where('session_id', session()->getId())->get();

        foreach ($oauth2_session as $session) {

            // Verify has a token
            if (!empty($session->token)) {

                // revoke refresh
                if (!empty($session->token->refreshToken)) {
                    $session->token->refreshToken->revoke();
                }

                // Revoke access token 
                $session->token->revoke();
            }
        }

        // delete all oauth session token 
        $this->model->where('session_id', $session_id)->delete();

        // Delete current session
        Session::find($session_id)->delete();
    }
}
