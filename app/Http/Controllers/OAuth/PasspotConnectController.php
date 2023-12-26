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
    }

    /**
     * gateway para verificar si almenos tiene un scope presente, esta solicitud
     * lleva encabezados Authorization, Scopes
     * @return null
     */
    public function check_scope(Request $request)
    {
    }

    /**
     * gateway para verificar si todos los scopes estan presentes, esta solicitud
     * lleva encabezados Authorization, Scopes
     * @return null
     */
    public function check_scopes(Request $request)
    {
    }

    /**
     * gateway para verificar si las credenciales del cliente son correctas, esta solicitud
     * lleva encabezados Authorization y Scopes es opcional
     * @return null
     */
    public function check_client_credentials(Request $request)
    {
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
        return $this->authenticated_user()['data'];
    }
}
