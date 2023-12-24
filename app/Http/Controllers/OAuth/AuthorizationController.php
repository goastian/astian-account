<?php
namespace App\Http\Controllers\OAuth;

use Illuminate\Http\Request;
use Illuminate\Support\Str; 
use Laravel\Passport\Bridge\User;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\ClientRepository; 
use Psr\Http\Message\ServerRequestInterface; 
use App\Exceptions\OAuthAuthenticationException; 
use Nyholm\Psr7\Response as Psr7Response;
use League\OAuth2\Server\Exception\OAuthServerException;
use Laravel\Passport\Http\Controllers\AuthorizationController as Controller;

class AuthorizationController extends Controller
{
   
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
                              TokenRepository $tokens)
    {
        $authRequest = $this->withErrorHandling(function () use ($psrRequest) {
            return $this->server->validateAuthorizationRequest($psrRequest);
        });

        if ($this->guard->guest()) {
            return $request->get('prompt') === 'none'
                    ? $this->denyRequest($authRequest)
                    : $this->promptForLogin($request);
        }

        if ($request->get('prompt') === 'login' &&
            ! $request->session()->get('promptedForLogin', false)) {
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
            $uri = $authRequest->getRedirectUri()
            ?? (is_array($authRequest->getClient()->getRedirectUri())
            ? $authRequest->getClient()->getRedirectUri()[0]
                    : $authRequest->getClient()->getRedirectUri());
                    
            $separator = $authRequest->getGrantTypeId() === 'implicit' ? '#' : '?';

            $uri = $uri.(str_contains($uri, $separator) ? '&' : $separator).'state='.$authRequest->getState();
            
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
}
