<?php

namespace App\Http\Controllers\OAuth;

use App\Models\OAuth\Client;
use Illuminate\Http\Request;
use Laravel\Passport\ClientRepository;
use Elyerr\ApiResponse\Exceptions\ReportError;
use App\Http\Controllers\GlobalController as Controller;

class ClientAdminController extends Controller
{

    /**
     * The client repository instance.
     *
     * @var \Laravel\Passport\ClientRepository
     */
    protected $clients;

    public function __construct(ClientRepository $clients)
    {
        parent::__construct();
        $this->clients = $clients;
        $this->middleware('scope:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Client $client)
    {
        $params = $this->filter_transform($client->transformer);

        $clients = $this->search($client->table, $params);
        $clients = collect($clients)->where('user_id', null)->values();

        return $this->showAll($clients, $client->transformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Client $client)
    {
        if ($client->user_id) {
            return new ReportError(__("Client not found"), 404);
        }

        $this->validate($request, [
            'name' => 'required|max:191',
            'redirect' => ['required', 'array'],
            'redirect.*' => ['required', 'distinct', 'url:http,https'],
            'confidential' => 'boolean',
        ]);

        $request->merge(['redirect' => implode(',', $request->redirect)]);

        $client = $this->clients->create(
            null, $request->name, $request->redirect,
            null, false, false, (bool) $request->input('confidential', true)
        );

        return $this->showOne($client, $client->transformer, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        return $this->showOne($client, $client->transformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        if ($client->user_id) {
            return new ReportError(__("Client not found"), 404);
        }

        $this->validate($request, [
            'name' => 'required|max:191',
            'redirect' => ['required', 'array'],
            'redirect.*' => ['required', 'distinct', 'url:http,https'],
        ]);

        $request->merge(['redirect' => implode(',', $request->redirect)]);

        $client = $this->clients->update(
            $client, $request->name, $request->redirect
        );

        return $this->showOne($client, $client->transformer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        if ($client->user_id) {
            return new ReportError(__("Client not found"), 404);
        }

        $this->clients->delete($client);

        $client->tokens()->update(['revoked' => true]);

        return $this->showOne($client, $client->transformer);
    }
}
