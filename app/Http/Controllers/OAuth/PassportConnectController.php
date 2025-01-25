<?php

namespace App\Http\Controllers\OAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController;

class PassportConnectController extends GlobalController
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
     * Gateway to verify if a user is authenticated. This request includes Authorization headers.
     *
     * @param Request $request
     *
     * @return void
     */
    public function check_authentication(Request $request)
    {
    }

    /**
     * Gateway to verify if at least one scope is present. This request includes Authorization and X-SCOPES headers.
     *
     * @return void
     */
    public function check_scope(Request $request)
    {
    }

    /**
     * Gateway to verify if all scopes are present. This request includes Authorization and X-SCOPES headers.
     *
     * @return void
     */
    public function check_scopes(Request $request)
    {
    }

    /**
     * Gateway to verify if client credentials are correct. This request includes Authorization header and optionally X-SCOPES header.
     *
     * @return void
     */
    public function check_client_credentials(Request $request)
    {
    }

    /**
     * Gateway to check if a token can execute a specific scope. This request includes Authorization and X-SCOPE headers.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function token_can(Request $request)
    {
        $scope = $request->header('X-SCOPE');

        $status = request()->user()->tokenCan($scope);

        return $status ? response(null, 200) : response(null, 403);
    }

    /**
     * Gateway to retrieve authenticated user data. This request includes Authorization header.
     *
     * @param Request $request
     *
     * @return Json
     */
    public function auth(Request $request)
    {
        return $this->authenticated_user();
    }

    /**
     * verify the email key
     *
     * @param string $value
     * @return bool
     */
    public function is_email($value)
    {
        $valid_email = filter_var($value, FILTER_VALIDATE_EMAIL);

        if ($valid_email) {
            list($usuario, $dominio) = explode('@', $value);

            if (strpos($dominio, '.') !== false) {
                list($dominio, $extension) = explode('.', $dominio);
                if (strlen($extension) >= 2) {
                    return true;
                }
            }
        }
        return false;
    }
}
