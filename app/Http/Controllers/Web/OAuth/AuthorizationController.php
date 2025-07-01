<?php
namespace App\Http\Controllers\Web\OAuth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Passport\Bridge\User;
use App\Repositories\Traits\Scopes;
use Laravel\Passport\ClientRepository;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Contracts\Auth\StatefulGuard;
use Psr\Http\Message\ServerRequestInterface;
use League\OAuth2\Server\AuthorizationServer;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Passport\Exceptions\OAuthServerException;
use Laravel\Passport\Exceptions\AuthenticationException;
use Laravel\Passport\Contracts\AuthorizationViewResponse;
use League\OAuth2\Server\RequestTypes\AuthorizationRequestInterface;
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
        protected AuthorizationServer $server,
        protected StatefulGuard $guard,
        protected ClientRepository $clients
    ) {
        parent::__construct($server, $guard, $clients); 
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
        ResponseInterface $psrResponse,
        AuthorizationViewResponse $viewResponse
    ): Response|AuthorizationViewResponse {

        $authRequest = $this->withErrorHandling(
            fn(): AuthorizationRequestInterface => $this->server->validateAuthorizationRequest($psrRequest),
            ($psrRequest->getQueryParams()['response_type'] ?? null) === 'token'
        );

        if ($this->guard->guest()) {
            switch ($request->get('prompt')) {
                case 'none':
                    throw OAuthServerException::loginRequired($authRequest);
                case 'internal':
                    return $this->internalPrompt($authRequest, $psrResponse);
                default:
                    $this->promptForLogin($request);
                    break;
            }
        }

        if (
            $request->get('prompt') === 'login' &&
            !$request->session()->get('promptedForLogin', false)
        ) {
            $this->guard->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $this->promptForLogin($request);
        }

        $request->session()->forget('promptedForLogin');

        $user = $this->guard->user();
        $authRequest->setUser(new User($user->getAuthIdentifier()));

        $scopes = $this->parseScopes($authRequest);
        $client = $this->clients->find($authRequest->getClient()->getIdentifier());

        if (
            $request->get('prompt') !== 'consent' &&
            ($client->skipsAuthorization($user, $scopes) || $this->hasGrantedScopes($user, $client, $scopes))
        ) {
            return $this->approveRequest($authRequest, $psrResponse);
        }

        if ($request->get('prompt') === 'none') {
            throw OAuthServerException::consentRequired($authRequest);
        }

        if ($request->get('prompt') === 'internal' && $client->private) {
            return $this->internalPrompt($authRequest, $psrResponse);
        }

        $request->session()->put('authToken', $authToken = Str::random());
        $request->session()->put('authRequest', $authRequest);

        return $viewResponse->withParameters([
            'client' => $client,
            'user' => $user,
            'scopes' => $scopes,
            'request' => $request,
            'authToken' => $authToken,
        ]);
    }

    /**
     * Prompt the user to login by throwing an AuthenticationException.
     *
     * @throws \Laravel\Passport\Exceptions\AuthenticationException
     */
    protected function promptForLogin(Request $request): never
    {
        $this->redirectTo();
        $request->session()->put('promptedForLogin', true);

        throw new AuthenticationException(guards: isset($this->guard->name) ? [$this->guard->name] : []);
    }

    /**
     * Handles the custom "internal" prompt type for trusted first-party applications.
     *
     * This flow allows internal applications to perform silent authorization if the user is
     * already authenticated. If the user is not logged in, they are redirected to the login page
     * and the authorization request is stored in session for continuation after login.
     *
     * If the user is authenticated, the request is automatically approved without requiring consent.
     *
     * @param  \League\OAuth2\Server\RequestTypes\AuthorizationRequestInterface  $authRequest
     * @param  \Psr\Http\Message\ResponseInterface  $responseInterface
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function internalPrompt($authRequest, ResponseInterface $responseInterface)
    {
        $user = auth()->user();

        if (is_null($user)) {

            $this->redirectTo();

            return redirect()->route('login');
        }

        $authRequest->setUser(new User($user->getAuthIdentifier()));

        $authRequest->setAuthorizationApproved(true);

        return $this->withErrorHandling(function () use ($authRequest, $responseInterface) {
            return $this->convertResponse(
                $this->server->completeAuthorizationRequest($authRequest, $responseInterface)
            );
        });
    }

    /**
     * Stores the intended authorization URL in the session
     * before redirecting the user to the login page.
     *
     * This ensures that after a successful authentication,
     * the user is redirected back to the original authorization request.
     *
     * @return void
     */
    private function redirectTo()
    {
        $authorize_route = route('passport.authorizations.authorize');
        $redirect_to = $authorize_route . "?" . http_build_query(request()->all());

        session()->put('redirect_to', $redirect_to);
    }

}
