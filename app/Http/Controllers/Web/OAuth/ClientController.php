<?php

namespace App\Http\Controllers\Web\OAuth;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use App\Http\Requests\Client\StoreRequest;
use App\Http\Requests\Client\UpdateRequest;
use App\Repositories\OAuth\ClientRepository;
use Elyerr\ApiResponse\Assets\JsonResponser;
use App\Transformers\OAuth\ClientTransformer;

class ClientController extends WebController
{
    use JsonResponser;

    /**
     * Client repository
     * @var ClientRepository
     */
    public $repository;


    public function __construct(ClientRepository $clientRepository)
    {
        parent::__construct();
        $this->repository = $clientRepository;
    }

    /**
     * Show clients form for users
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request)
    {
        if (request()->wantsJson()) {
            return $this->repository->findClientsForUser($request);
        }

        return Inertia::render("OAuth/Clients/Index", [
            'route' => route('passport.clients.index')
        ]);
    }

    /**
     * Create a new client
     * @param \Illuminate\Http\Request $request
     * @return array|mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $client = $this->repository->createClientForUser($request);

        return $this->showOne($client, ClientTransformer::class, 201);
    }


    /**
     * Update client
     * @param \App\Http\Requests\Client\UpdateRequest $request
     * @param string|int $clientId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, string|int $clientId)
    {
        $client = $this->repository->updateClientForUser($request, $clientId);

        return $this->showOne($client, ClientTransformer::class);
    }

    /**
     * Delete client for user
     * @param \Illuminate\Http\Request $request
     * @param string|int $clientId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, string|int $clientId)
    {
        return $this->repository->deleteClientForUser($request, $clientId);
    }
}
