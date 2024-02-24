<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\GlobalController;
use App\Models\Auth\Session;
use App\Transformers\Auth\EmployeeTransformer;
use Error;
use ErrorException;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Cookie\CookieValuePrefix;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Http\Request;
use Laravel\Passport\RefreshTokenRepository;
use Laravel\Passport\TokenRepository;

class AuthorizationController extends GlobalController
{

    /**
     * Encrypter
     *
     * @var \Illuminate\Contracts\Encryption\Encrypter
     */
    public $encrypter;

    public function __construct(Encrypter $encrypter)
    {
        parent::__construct();
        $this->encrypter = $encrypter;
        $this->middleware('transform.request:' . EmployeeTransformer::class)->only('store');
    }

    /**
     * Destroy the session
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {

        try {
            $access_token_id = $this->decode_token($request) ?: request()->user()->token()->id;

            $tokenRepository = app(TokenRepository::class);
            $refreshTokenRepository = app(RefreshTokenRepository::class);

            $tokenRepository->revokeAccessToken($access_token_id);
            $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($access_token_id);

        } catch (ErrorException $e) {}

        $this->destroy_default_session($request);

        return $this->message(__('Session has finished'), 200);
    }

    /**
     * Get and decode the token jwt from the microservices
     *
     * @param Request $request
     * @return String
     */
    public function decode_token(Request $request)
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
    public function destroy_default_session(Request $request)
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
        } catch (Error $e) {}

    }
}
