<?php

namespace App\Http\Controllers\OAuth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Passport\Http\Controllers\ClientController as Controller;
use Laravel\Passport\Passport;

class ClientController extends Controller
{
    /**
     * Store a new client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Laravel\Passport\Client|array
     */
    public function store(Request $request)
    {
        if ($this->existsForThisUser($request)) {
            $this->validation->make($request->only('redirect'), [
                'redirect' => ['unique:oauth_clients,redirect'],
            ])->validate();
        }

        $this->validation->make($request->all(), [
            'name' => 'required|max:191',
            'redirect' => ['required', $this->redirectRule],
            'confidential' => 'boolean',
        ])->validate();

        $client = $this->clients->create(
            $request->user()->getAuthIdentifier(), $request->name, $request->redirect,
            null, false, false, (bool) $request->input('confidential', true)
        );

        if (Passport::$hashesClientSecrets) {
            return ['plainSecret' => $client->plainSecret] + $client->toArray();
        }

        return $client->makeVisible('secret');
    }

    /**
     * verifica que el mismo cliente no registre pa un usuario
     */
    public function existsForThisUser($request)
    {
        $userId = $request->user()->getAuthIdentifier();
        $clients = $this->clients->activeForUser($userId);
        return $clients->where('redirect', $request->redirect)->first();
    }

    /**
     * Update the given client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $clientId
     * @return \Illuminate\Http\Response|\Laravel\Passport\Client
     */
    public function update(Request $request, $clientId)
    {
        $client = $this->clients->findForUser($clientId, $request->user()->getAuthIdentifier());

        if (!$client) {
            return new Response('', 404);
        }
        $clientForDB = $this->existsForThisUser($request);

        if ($clientForDB->user_id == $client->user_id and $clientForDB->redirect != $client->redirect) {
            $this->validation->make($request->only('redirect'), [
                'redirect' => ['unique:oauth_clients,redirect'],
            ])->validate();
        }

        $this->validation->make($request->all(), [
            'name' => 'required|max:191',
            'redirect' => ['required', $this->redirectRule],
        ])->validate();

        return $this->clients->update(
            $client, $request->name, $request->redirect
        );
    }

}