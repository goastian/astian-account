<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Passport\TokenRepository;
use App\Http\Requests\Auth\LoginRequest;
use Elyerr\ApiResponse\Events\LoginEvent;
use Elyerr\ApiResponse\Events\LogoutEvent;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Laravel\Passport\RefreshTokenRepository;
use App\Transformers\Auth\EmployeeTransformer;

class AuthorizationController extends Controller
{
    use JsonResponser;

    public function __construct()
    {
        $this->middleware('guest')->only('store');
        $this->middleware('auth:api')->only('destroy');
        $this->middleware('transform.request:' . EmployeeTransformer::class)->only('store');
    }
    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $scoupes = $request->user()->roles()->get()->pluck('name')->implode(',');

        $token = request()->user()->createToken($_SERVER['HTTP_USER_AGENT'], explode(',', $scoupes))->accessToken;

        LoginEvent::dispatch(request()->user());

        return response()->json(['data' => [
            'Authorization' => $token,
        ]], 201);
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
