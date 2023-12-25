<?php

namespace App\Http\Controllers\OAuth;

use Error;
use Illuminate\Http\Request;
use Laravel\Passport\Http\Controllers\DenyAuthorizationController as Controller;
use Nyholm\Psr7\Response as Psr7Response;

class DenyAuthorizationController extends Controller
{
    /**
     * Deny the authorization request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deny(Request $request)
    {
        $this->assertValidAuthToken($request);

        $authRequest = $this->getAuthRequestFromSession($request);

        $authRequest->setAuthorizationApproved(false);

        try {
            return $this->withErrorHandling(function () use ($authRequest) {
                return $this->convertResponse(
                    $this->server->completeAuthorizationRequest($authRequest, new Psr7Response)
                );
            });
        } catch (Error $e) {
            return redirect()->route('login');
        }
    }
}
