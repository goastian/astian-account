<?php
namespace App\Http\Controllers\Web\OAuth;
 
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Passport\Bridge\User;
use App\Repositories\Traits\Scopes;
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
    public function __construct(
        AuthorizationServer $server,
        StatefulGuard $guard,
        AuthorizationViewResponse $response
    ) {
        parent::__construct($server, $guard, $response);
        
        $this->userHasScopes(request());

    }

    /**
     * Authorize a client to access the user's account.
     * @param \Psr\Http\Message\ServerRequestInterface $psrRequest
     * @param \Illuminate\Http\Request $request
     * @param \Laravel\Passport\ClientRepository $clients
     * @param \Laravel\Passport\TokenRepository $tokens
     * @return AuthorizationViewResponse|\Illuminate\Http\Response
     */
    public function authorize(
        ServerRequestInterface $psrRequest,
        Request $request,
        ClientRepository $clients,
        TokenRepository $tokens
    ) {

        $authRequest = $this->withErrorHandling(function () use ($psrRequest) {
            return $this->server->validateAuthorizationRequest($psrRequest);
        });

        if ($this->guard->guest()) {
            return $request->get('prompt') === 'none'
                ? $this->denyRequest($authRequest)
                : $this->promptForLogin($request);
        }

        if (
            $request->get('prompt') === 'login' &&
            !$request->session()->get('promptedForLogin', false)
        ) {
            $this->guard->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return $this->promptForLogin($request);
        }

        $request->session()->forget('promptedForLogin');

        $scopes = $this->parseScopes($authRequest);

        $user = $this->guard->user();

        $client = $clients->find($authRequest->getClient()->getIdentifier());

        if (
            $request->get('prompt') !== 'consent' &&
            ($client->skipsAuthorization() || $this->hasValidToken($tokens, $user, $client, $scopes))
        ) {
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
     * Check available scopes to the requested user
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function userHasScopes(Request $request)
    {
        $scopes_accepted = [];

        $request_scopes = explode(' ', $request->scope);

        $owner_scopes = collect($this->scopes())->pluck('id');

        if (str_contains($request->scope, '*')) {
            foreach ($owner_scopes as $key) {
                array_push($scopes_accepted, $key);
            }
        } else {
            foreach ($owner_scopes as $key) {
                if (in_array($key, $request_scopes)) {
                    array_push($scopes_accepted, $key);
                }
            }
        }
        $request->merge(['scope' => implode(" ", $scopes_accepted)]);
    }
}
