<?php

namespace App\Http\Middleware;

use App\Models\OAuth\Client;
use App\Models\OAuth\CsrfToken;
use Closure;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PassportCsrf
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $this->verifyCsrfToken($request);

        if ($this->isRefreshTokenOrAuthorizationCode($request) && isset($request->client_secret)) {

            $request->merge(['client_secret' => $this->verifyClientSecret($request)]);
        }

        $response = $next($request);

        $this->sendCsrfRefresh($request, $response);

        return $response;
    }

    /**
     * actua cuando la peticion es a traves del grant_type refresh_token
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function isRefreshTokenOrAuthorizationCode($request)
    {
        return in_array($request->grant_type, ['authorization_code', 'refresh_token']);
    }

    /**
     * verifica el csrf token
     * @param  Illuminate\Http\Request $request
     * @return void
     */
    public function verifyCsrfToken($request)
    {
        //get csrf headers
        $csrf = $request->header('X-CSRF-TOKEN') ?: $request->header('X-CSRF-REFRESH');
        
        if (!CsrfToken::findToken($csrf, $request->client_id, $request->grant_type)) {
            throw new ReportError(__("El tiempo esperado para que el cliente solitara el token ha caducado"), 406);
        }
    }

    /**
     * verifica el client_secret
     *  @param  Illuminate\Http\Request $request
     * @return String
     */
    public function verifyClientSecret($request)
    {
        //get client
        $client = Client::find($request->client_id);

        //verify the secret key is valid
        if (!Hash::check($client->secret, $request->client_secret)) {
            throw new ReportError(__('el servidor no puede procesar la solicitud por que los datos del cliente no coinciden con los del servidor'), 406);
        }

        return $client->secret;
    }

    /**
     * envia un csrfToken de un solo uso a travez de la cabecera
     * cuando el tipo de grant_type se solo authorization_code y refresh_token
     */
    public function sendCsrfRefresh($request, $response)
    {
        if ($this->isRefreshTokenOrAuthorizationCode($request)) {

            $csrf = CsrfToken::generateToken($request->client_id);

            $response->header('X-CSRF-REFRESH', $csrf->token);
        }
    }
}
