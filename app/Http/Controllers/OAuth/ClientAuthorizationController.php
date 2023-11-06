<?php

namespace App\Http\Controllers\OAuth;

use App\Http\Controllers\GlobalController as Controller;
use App\Http\Controllers\OAuth\Scopes;
use App\Models\OAuth\Client;
use App\Providers\RouteServiceProvider;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;
use Laravel\Passport\ClientRepository;

class ClientAuthorizationController extends Controller
{
    use Scopes;

    public $client;

    public $userId;

    public $response_types;

    public function __construct(ClientRepository $client)
    {
        $this->middleware('auth');
        $this->client = $client;
        $this->response_types = ['confidential', 'code'];
    }

    /**
     * show view for user select scopes
     * @return Illuminate\View\View
     */
    public function grantAccess()
    {
        $scopes = $this->scopes();
        $params = RouteServiceProvider::query();

        return view('auth.grant-access', ['scopes' => $scopes, 'params' => $params]);
    }

    /**
     * recupera todo los parametros y los parametros de la url y los scopes
     * @return void
     */
    public function sendForAuthorize(Request $request)
    {
        /**
         * denegando authorizacion por tipo de response type
         */
        throw_unless(in_array($request->response_type, $this->response_types),
            new ReportError('El sistema de authorizacion no cumple con los estandares establecidos', 403));

        $authorization = "http://auth.spondylus.xyz/oauth/authorize";

        /**
         * recuperando parametros 
         */
        $scopes = $request->only('scopes') ? implode(' ', $request->only('scopes')['scopes']) : ' ';
        $params = $request->except('scopes');
        $params['scope'] = $scopes;
        $params['prompt'] = 'consent';

        /**
         * authorizacion para cliente publico
         */
        if ($request->response_type == 'code') {
            $query = http_build_query($params);
            return redirect($authorization . "?$query");
        }

        /**
         * authorizacion para cliente confidencial
         */
        $client = $this->findClient($request, $request->redirect_uri);
        throw_unless($client,
            new ReportError('por favor registra al cliente antes de usar este metho de authorizacion', 400));

        $params['response_type'] = 'code';
        $params['client_id'] = $client->id;

        $query = http_build_query($params);

        return redirect($authorization . "?$query");
    }

    /**
     * busca a un cliente usando la url que pertenezca al usuario logeado
     * @param \Illuminate\Http\Request $request
     * @param String
     * @return Object
     */
    public function findClient(Request $request, $uri)
    {
        $client = Client::where([
            'redirect' => $uri,
            'user_id' => $request->user()->id,
        ])->get()->reject(function ($client) {
            return $client->revoked;
        })->first();

        return $client ?: false;
    }

    /**
     * Crea un cliente
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\OAuth\Client
     */
    public function storeClient(Request $request)
    {
        $userId = $request->user()->id;
        $name = $this->clientName($request);
        $redirect = $request->redirect_uri;

        $this->client->create($userId, $name, $redirect, null, false, false, true);
    }

    /**
     * obtine el nombre del cliente
     * @param \Illuminate\Http\Request $request
     * @return String
     */
    public function clientName(Request $request)
    {
        $name = explode('.', $request->redirect_uri)[0];
        if (str_starts_with($name, 'https://')) {
            $name = str_replace('https://', '', $name);
        } else {
            $name = str_replace('http://', '', $name);
        }
        return $name;
    }
}
