<?php

namespace App\Http\Controllers\Auth;

use Error;
use ErrorException;
use App\Models\Auth\Session;
use Illuminate\Http\Request;
use Laravel\Passport\TokenRepository;
use Illuminate\Cookie\CookieValuePrefix;
use App\Http\Controllers\GlobalController;
use Laravel\Passport\RefreshTokenRepository;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Contracts\Encryption\DecryptException;

class AuthorizationController extends GlobalController
{

    /**
     * Encrypter
     *
     * @var \Illuminate\Contracts\Encryption\Encrypter
     */
    public $encrypter;

    /**
     * Class construct
     * @param \Illuminate\Contracts\Encryption\Encrypter $encrypter
     */
    public function __construct(Encrypter $encrypter)
    {
        parent::__construct();
        $this->encrypter = $encrypter;
    }

    /**
     * destroy sessions
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        try {
            $access_token_id = $this->decodeToken($request) ?: request()->user()->token()->id;

            $tokenRepository = app(TokenRepository::class);
            $refreshTokenRepository = app(RefreshTokenRepository::class);

            $tokenRepository->revokeAccessToken($access_token_id);
            $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($access_token_id);

        } catch (ErrorException $e) {
        }

        $this->destroyDefaultSession($request);

        return $this->message(__('Session has finished'), 200);
    }

    /**
     * Decode the token jwt
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function decodeToken(Request $request)
    {
        try {
            $token = $request->cookie(env('PASSPORT_TOKEN'));

            $token_decode = explode('.', $token);
            $token_decode = $token_decode[1];
            $token_decode = json_decode(base64_decode($token_decode));
            return $token_decode->jti;
        } catch (ErrorException $e) {
            return null;
        }
    }

    /**
     * Remove default session from laravel
     *
     * @param Request $request
     * @return void
     */
    public function destroyDefaultSession(Request $request)
    {
        /**
         * decoding cookie from the default session from laravel
         */
        try {
            if ($request->hasCookie(config('session.cookie'))) {
                $value = $request->cookie(config('session.cookie'));
                try {
                    $session_id = CookieValuePrefix::remove(
                        $this->encrypter->decrypt(
                            $value,
                            EncryptCookies::serialized(config('session.cookie'))
                        )
                    );
                } catch (DecryptException $e) {
                    $session_id = $value;
                }
            }
            Session::find($session_id)->delete();
        } catch (Error $e) {
        } catch (ErrorException $e) {
        }

    }
}
