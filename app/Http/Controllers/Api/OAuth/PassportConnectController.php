<?php
namespace App\Http\Controllers\Api\OAuth;

use App\Traits\Scopes;
use Illuminate\Http\Request; 
use App\Http\Controllers\ApiController; 
use App\Repositories\OAuth\Server\Grant\OAuthSessionTokenRepository;

class PassportConnectController extends ApiController
{

    use Scopes;

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
     * Check if the user has scope
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function token_can(Request $request)
    {
        $scope = $request->header('X-SCOPE');

        if (request()->user()->tokenCan($scope)) {
            return response(null, 200);
        }

        return response(null, 403);
    }

    /**
     * Gateway to get information about the authenticatable user
     * @param \Illuminate\Http\Request $request
     */
    public function authenticated(Request $request)
    {
        return $this->authenticated_user();
    }

    /**
     * Revoke authorization to the current client
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function revokeAuthorization(Request $request, OAuthSessionTokenRepository $oAuthSessionTokenRepository)
    {
        $token = auth()->user()->token();

        $session = $oAuthSessionTokenRepository->findToken($token->id);

        $oAuthSessionTokenRepository->destroyTokenSession($session->getSessionId());

        return $this->message(__('Session finished successfully'), 200);
    }

    /**
     * Get the all available scopes for the auth user
     */
    public function access()
    {
        return $this->availableScopes();
    }
}
