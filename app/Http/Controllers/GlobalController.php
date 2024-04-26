<?php

namespace App\Http\Controllers;

use App\Transformers\Auth\EmployeeTransformer;
use Elyerr\ApiResponse\Assets\Asset;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Elyerr\Echo\Client\PHP\Socket\Socket;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\RefreshTokenRepository;
use Laravel\Passport\TokenRepository;

class GlobalController extends Controller
{
    use AuthorizesRequests, Socket, DispatchesJobs, ValidatesRequests, JsonResponser, Asset;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function authenticated_user()
    {
        $user = fractal(Auth::user(), EmployeeTransformer::class);
         
        return  json_decode(json_encode($user))->data;
    }

    /**
     * revoca todas las credenciales generadas del usuario authenticado
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
