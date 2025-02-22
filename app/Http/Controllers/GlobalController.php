<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\TokenRepository;
use App\Transformers\User\UserTransformer;
use Laravel\Passport\RefreshTokenRepository;

class GlobalController extends Controller
{
    /**
     * Construct of class
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Return information about the current users and transform date in the process
     * @return mixed
     */
    public function authenticated_user()
    {
        $user = fractal(Auth::user(), UserTransformer::class);

        return json_decode(json_encode($user))->data;
    }

    /**
     * Remove the all credential 
     * @param mixed $tokens
     * @return void
     */
    public function removeCredentials($tokens)
    {
        $tokenRepository = app(TokenRepository::class);
        $refreshTokenRepository = app(RefreshTokenRepository::class);

        foreach ($tokens as $token) {

            $tokenRepository->revokeAccessToken($token->id);

            $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);
        }
    }
}
