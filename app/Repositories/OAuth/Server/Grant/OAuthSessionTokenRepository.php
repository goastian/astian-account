<?php
namespace App\Repositories\OAuth\Server\Grant;

use App\Models\OAuth\Token;
use App\Models\Setting\Session;
use Laravel\Passport\TokenRepository;
use App\Models\OAuth\OAuthSessionToken;
use Laravel\Passport\RefreshTokenRepository;


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
     * Revoke the all tokens of the session
     * @param string $session_id
     * @return void
     */
    public function destroyTokenSession(string $session_id)
    {
        // Retrieve the current session
        $source = $this->model->where('session_id', session()->getId())->get();

        // Instance repositories
        $tokenRepository = app(TokenRepository::class);
        $refreshTokenRepository = app(RefreshTokenRepository::class);

        foreach ($source as $key) {
            // Searching by token id
            $token = Token::find($key->oauth_access_token_id);

            // Revoke access token
            if (!empty($token)) {
                $tokenRepository->revokeAccessToken($token->id);
                // Revoke refresh token
                $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);
            }

        }
        // delete all oauth session token 
        $this->model->where('session_id', $session_id)->delete();

        Session::find($session_id)->delete();
    }
}
