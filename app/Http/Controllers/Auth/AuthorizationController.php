<?php

namespace App\Http\Controllers\Auth;

use App\Events\Auth\LogoutEvent;
use App\Http\Controllers\Controller;
use Laravel\Passport\TokenRepository;  
use Elyerr\ApiResponse\Assets\JsonResponser;
use Laravel\Passport\RefreshTokenRepository;
use App\Transformers\Auth\EmployeeTransformer;

class AuthorizationController extends Controller
{
    use JsonResponser;

    public function __construct()
    { 
        $this->middleware('auth:api')->only('destroy');
        $this->middleware('transform.request:' . EmployeeTransformer::class)->only('store');
    }
     
    /**
     * Destruye la sesion en el cliente invalidado las credenciales.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        $accessToken = request()->user()->token();

        $tokenRepository = app(TokenRepository::class);
        $refreshTokenRepository = app(RefreshTokenRepository::class);

        $tokenRepository->revokeAccessToken($accessToken->id);

        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($accessToken->id);

        LogoutEvent::dispatch();

        return $this->message('La sesion ha sido cerrada.', 201);
    }
}
