<?php

namespace App\Http\Controllers;

use App\Transformers\Auth\EmployeeTransformer;
use Elyerr\ApiResponse\Assets\Asset;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\RefreshTokenRepository;
use Laravel\Passport\TokenRepository;

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
        return fractal(Auth::user(), EmployeeTransformer::class)->toArray();
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
