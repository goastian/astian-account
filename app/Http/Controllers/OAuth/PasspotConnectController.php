<?php

namespace App\Http\Controllers\OAuth;

use App\Http\Controllers\GlobalController;

class PasspotConnectController extends GlobalController
{

    public function __construct()
    {
        //headers
        $scopes = request()->header('Scopes');

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
     * gateway para verificar si un usuario esta autenticado
     * @return null
     */
    public function check_authentication()
    {
    }

    /**
     * gateway para verificar si almenos tiene un scope presente
     * @return @return null
     */
    public function check_scope()
    {
    }

    /**
     * gateway para verificar si todos los scopes estan presentes
     * @return @return null
     */
    public function check_scopes()
    {
    }

    /**
     * gateway para verificar si las credenciales del cliente son correctas
     * @return @return null
     */
    public function check_client_credentials()
    {
    }
}
