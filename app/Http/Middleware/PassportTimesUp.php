<?php

namespace App\Http\Middleware;

use App\Models\OAuth\Client;
use App\Models\OAuth\CsrfToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PassportTimesUp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response->isRedirection()) {

            $client = Client::find($request->client_id);

            $url = $response->headers->get('Location');

            $id = $client->id;

            $csrfToken = $this->CsrfToken($client);

            $secret = Hash::make($client->secret);

            $url .= isset($client->secret) ?
            "?id=$id?secret=$secret?csrf=$csrfToken" :
            "?id=$id?csrf=$csrfToken";

            return redirect($url, 302);
        }

        return $response;

    }

    /**
     * genera un token unico de un solo uso que debe usarse cundo
     * se intercambie el codigo de authorizacion por credenciales
     * @param \App\Models\OAuth\Client $client
     * @return String
     */
    public function CsrfToken($client)
    {
        $csrfToken = CsrfToken::generateToken($client->id);

        return $csrfToken->token;
    }
}
