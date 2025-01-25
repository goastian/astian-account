<?php
namespace App\Http\Controllers\OAuth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Passport\Bridge\User;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\ClientRepository;
use Nyholm\Psr7\Response as Psr7Response;
use Illuminate\Contracts\Auth\StatefulGuard;
use Psr\Http\Message\ServerRequestInterface;
use League\OAuth2\Server\AuthorizationServer;
use App\Exceptions\OAuthAuthenticationException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Laravel\Passport\Contracts\AuthorizationViewResponse;
use Laravel\Passport\Http\Controllers\AuthorizationController as Controller;

class AuthorizationController extends Controller
{

    use Scopes;

    /**
     * Create a new controller instance.
     *
     * @param  \League\OAuth2\Server\AuthorizationServer  $server
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @param  \Laravel\Passport\Contracts\AuthorizationViewResponse  $response
     * @return void
     */
    public function __construct(AuthorizationServer $server,
        StatefulGuard $guard,
        AuthorizationViewResponse $response) {
        parent::__construct($server, $guard, $response);

        $this->scopes_can_granted(request());

    }

    /**
     * Authorize a client to access the user's account.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface  $psrRequest
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Passport\ClientRepository  $clients
     * @param  \Laravel\Passport\TokenRepository  $tokens
     * @return \Illuminate\Http\Response|\Laravel\Passport\Contracts\AuthorizationViewResponse
     */
    public function authorize(ServerRequestInterface $psrRequest,
        Request $request,
        ClientRepository $clients,
        TokenRepository $tokens) {

        $authRequest = $this->withErrorHandling(function () use ($psrRequest) {
            return $this->server->validateAuthorizationRequest($psrRequest);
        });

        if ($this->guard->guest()) {
            return $request->get('prompt') === 'none'
            ? $this->denyRequest($authRequest)
            : $this->promptForLogin($request);
        }

        if ($request->get('prompt') === 'login' &&
            !$request->session()->get('promptedForLogin', false)) {
            $this->guard->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return $this->promptForLogin($request);
        }

        $request->session()->forget('promptedForLogin');

        $scopes = $this->parseScopes($authRequest);

        $user = $this->guard->user();

        $client = $clients->find($authRequest->getClient()->getIdentifier());

        if ($request->get('prompt') !== 'consent' &&
            ($client->skipsAuthorization() || $this->hasValidToken($tokens, $user, $client, $scopes))) {
            return $this->approveRequest($authRequest, $user);
        }

        if ($request->get('prompt') === 'none') {
            return $this->denyRequest($authRequest, $user);
        }

        $request->session()->put('authToken', $authToken = Str::random());
        $request->session()->put('authRequest', $authRequest);

        return $this->response->withParameters([
            'client' => $client,
            'user' => $user,
            'scopes' => $scopes,
            'request' => $request,
            'authToken' => $authToken,
        ]);
    }

    /**
     * Prompt the user to login by throwing an OAuthAuthenticationException.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @throws \App\Exceptions\OAuthAuthenticationException
     */
    protected function promptForLogin($request)
    {
        $request->session()->put('promptedForLogin', true);

        throw new OAuthAuthenticationException;
    }

    /**
     * Deny the authorization request.
     *
     * @param  \League\OAuth2\Server\RequestTypes\AuthorizationRequest  $authRequest
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $user
     * @return \Illuminate\Http\Response
     */
    protected function denyRequest($authRequest, $user = null)
    {
        if (is_null($user)) {
            $uri = $authRequest->getRedirectUri() ?? (is_array($authRequest->getClient()->getRedirectUri())
                ? $authRequest->getClient()->getRedirectUri()[0]
                : $authRequest->getClient()->getRedirectUri());

            $separator = $authRequest->getGrantTypeId() === 'implicit' ? '#' : '?';

            $uri = $uri . (str_contains($uri, $separator) ? '&' : $separator) . 'state=' . $authRequest->getState();

            return $this->withErrorHandling(function () use ($uri) {
                throw OAuthServerException::accessDenied('Unauthenticated', $uri);
            });
        }

        $authRequest->setUser(new User($user->getAuthIdentifier()));

        $authRequest->setAuthorizationApproved(true);

        return $this->withErrorHandling(function () use ($authRequest) {
            return $this->convertResponse(
                $this->server->completeAuthorizationRequest($authRequest, new Psr7Response)
            );
        });
    }

    /**
     * verifica los scopes entrantes y solo asignara al los que el cliente tenga acceso
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    public function scopes_can_granted(Request $request)
    {
        //variable para lo nuevos scopes
        $scopes_accepted = [];

        //convertimos los scopes a un array
        $request_scopes = explode(' ', $request->scope);

        //obtenemos todos los scopes a los que el usuario tiene acceso
        $owner_scopes = collect($this->scopes())->pluck('id');

        /**
         * cuando alguien solicite un token global en el scope debe venir un asterisco (*)
         */
        if (str_contains($request->scope, '*')) {
            foreach ($owner_scopes as $key) {
                array_push($scopes_accepted, $key);
            }
        } else { //si no es un token global
            //verificamos si el scope que ingreso el usuario tiene accesso
            foreach ($owner_scopes as $key) {

                if (in_array($key, $request_scopes)) { //si tiene accesso se le asigna
                    array_push($scopes_accepted, $key);
                }
            }
        }

        $request->merge(['scope' => implode(" ", $scopes_accepted)]);
    }
}
