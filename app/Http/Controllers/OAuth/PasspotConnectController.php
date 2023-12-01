<?php

namespace App\Http\Controllers\OAuth;

use App\Http\Controllers\GlobalController;
use App\Models\OAuth\CsrfToken;
use DateInterval;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;

class PasspotConnectController extends GlobalController
{

    public function __construct()
    {
        //headers
        $scopes = request()->header('X-SCOPES');

        parent::__construct();
        $this->middleware('scope:' . $scopes)->only('check_scope');
        $this->middleware('scopes:' . $scopes)->only('check_scopes');

        if (isset($scopes)) {
            $this->middleware('client:' . $scopes)->only('check_client_credentials');
        } else {
            $this->middleware('client')->only('check_client_credentials');
        }
    }

    /**
     * gateway para verificar si un usuario esta autenticado, esta solicitud
     * lleva encabezados Authorization
     * @return null
     */
    public function check_authentication(Request $request)
    {
        //$this->verify_transaction($request);
    }

    /**
     * gateway para verificar si almenos tiene un scope presente, esta solicitud
     * lleva encabezados Authorization, Scopes
     * @return null
     */
    public function check_scope(Request $request)
    {
       // $this->verify_transaction($request);
    }

    /**
     * gateway para verificar si todos los scopes estan presentes, esta solicitud
     * lleva encabezados Authorization, Scopes
     * @return null
     */
    public function check_scopes(Request $request)
    {
       // $this->verify_transaction($request);
    }

    /**
     * gateway para verificar si las credenciales del cliente son correctas, esta solicitud
     * lleva encabezados Authorization y Scopes es opcional
     * @return null
     */
    public function check_client_credentials(Request $request)
    {
        //$this->verify_transaction($request);
    }

    /**
     * gateway para comprobar si un token puede ejecutar un scope, esta solicitud
     * lleva encabezados Authorization, Scope
     *
     * @param Request $request     *
     * @return null
     */
    public function token_can(Request $request)
    {
       // $this->verify_transaction($request);

        $scope = $request->header('X-SCOPE');

        $status = request()->user()->tokenCan($scope);

        return $status ? response(null, 200) : response(null, 403);
    }

    /**
     * gateway que permite obtener los datos del usuario autenticado
     *
     * @param Request $request
     */
    public function auth(Request $request)
    {
        /*if ($request->header('Authorization')) {

            $this->verify_transaction($request);
        }*/

        return $this->authenticated_user()['data'];
    }

    /**
     * verificar si en request existe un header X-Verify-Transaction
     *
     * @param Request $request
     * @return void
     */
    public function verify_transaction(Request $request)
    {
        $verify_transaction = $request->header('X-Verify-Transaction');

        /**
         * verifica si viene el token csrf
         */
        throw_unless($verify_transaction,
            new ReportError("No se encontro el token csrf", 403));

        /**
         * Obtemos el token del usuario actual
         */
        $token = $request->user()->token();

        /**
         * Obtenemos el token csrf
         */
        $csrfToken = CsrfToken::where('token', $verify_transaction)->first();

        throw_unless($csrfToken->client_id == $token->client_id,
            new ReportError("El token CSRF es invalido", 403));

        /**
         * verificamos la caduciodad del token CSRF
         */
        $token_expires_at = $token->expires_at->add(new DateInterval("PT" . env('PASSPORT_TOKEN_EXPIRE') . "S"));

        throw_unless($token_expires_at > now(),
            new ReportError("El token CSRF es invalido", 403));

    }
}
