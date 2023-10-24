<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Elyerr\ApiResponse\Assets\Asset;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\TokenRepository;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Laravel\Passport\RefreshTokenRepository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Transformers\Auth\EmployeeTransformer;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 

class GlobalController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, JsonResponser, Asset;

    public $can_update = array();

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function authenticated_user()
    {
        return fractal(Auth::user(), EmployeeTransformer::class);
    }

    public function lowercase($value)
    {
        return strtolower($value);
    }

    public function uppercase($value)
    {
        return strtoupper($value);
    }

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
