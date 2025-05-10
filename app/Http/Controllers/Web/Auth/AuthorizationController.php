<?php
namespace App\Http\Controllers\Web\Auth;
 

use Error;
use ErrorException;
use Illuminate\Http\Request;
use App\Models\Setting\Session;
use Illuminate\Support\Facades\Auth; 
use App\Http\Controllers\WebController;
use Illuminate\Cookie\CookieValuePrefix; 
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Contracts\Encryption\DecryptException;

class AuthorizationController extends WebController
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
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return $this->message(__('Session finished successfully'), 200);
        }

        return redirect('/');
    }

    /**
     * Decode the token jwt
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function decodeToken(Request $request)
    {
        try {
            $token = $request->cookie(config('system.passport_token_services', 'passport_token'));

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
