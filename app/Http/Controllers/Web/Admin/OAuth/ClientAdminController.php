<?php

namespace App\Http\Controllers\Web\Admin\OAuth;

use Inertia\Inertia;
use App\Models\OAuth\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use App\Http\Requests\Client\StoreRequest;
use App\Http\Requests\Client\UpdateRequest;
use App\Repositories\OAuth\ClientRepository;

class ClientAdminController extends WebController
{
    /**
     * The client repository instance.
     *
     * @var  ClientRepository
     */
    protected $repository;

    public function __construct(ClientRepository $clientRepository)
    {
        parent::__construct();
        $this->repository = $clientRepository;
        $this->middleware('userCanAny:administrator_application_full,administrator_application_view')->only('index');
        $this->middleware('userCanAny:administrator_application_full,administrator_application_show')->only('show');
        $this->middleware('userCanAny:administrator_application_full,administrator_application_create')->only('store');
        $this->middleware('userCanAny:administrator_application_full,administrator_application_update')->only('update');
        $this->middleware('userCanAny:administrator_application_full,administrator_application_destroy')->only('destroy');
    }

    /**
     * Retrieve the all clients for admin users
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return $this->repository->searchClientsForAdmin($request);
        }

        return Inertia::render("Admin/Clients/Index", [
            "route" => route("admin.clients.index")
        ]);
    }

    /**
     * Create admin resource
     * @param \App\Http\Requests\Client\StoreRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        return $this->repository->createClientForAdmin($request->toArray());
    }

    /**
     * Display specific resource for admin
     * @param \App\Models\OAuth\Client $client
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Client $client)
    {
        return $this->repository->retrieveClientForAdmin($client->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Client $client)
    {
        return $this->repository->updateClientForAdmin($client->id, $request->toArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        return $this->repository->revokeClientForAdmin($client->id);
    }
}
