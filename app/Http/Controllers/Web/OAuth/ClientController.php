<?php

namespace App\Http\Controllers\Web\OAuth;

use App\Rules\BooleanRule;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\DB;
use Elyerr\ApiResponse\Assets\JsonResponser;
use App\Transformers\OAuth\ClientTransformer;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Laravel\Passport\Http\Controllers\ClientController as Controller;

class ClientController extends Controller
{
    use JsonResponser;


    /**
     * Show all
     * @param mixed $collection
     * @param mixed $transformer
     * @param mixed $code
     * @param mixed $pagination
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function showAllByArray($collection, $transformer = null, $code = 200, $pagination = true)
    {
        $collection = $this->orderBy($collection);

        if ($pagination) {
            $collection = $this->paginate($collection);
        }

        if ($transformer != null && gettype($transformer) != "integer") {
            $collection = fractal($collection, $transformer);
        }

        return $collection->toArray();
    }

    /**
     * Summary of forUser
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\JsonResponse
     */
    public function forUser(Request $request)
    {
        $userId = $request->user()->getAuthIdentifier();

        $clients = $this->clients->activeForUser($userId);

        if (Passport::$hashesClientSecrets) {
            return $clients;
        }

        $clients->makeVisible('secret');
        if (request()->wantsJson()) {
            return $this->showAll($clients, ClientTransformer::class);
        }

        return Inertia::render("OAuth/Clients/Index", [
            'clients' => $this->showAllByArray($clients, ClientTransformer::class),
            'route' => route('passport.clients.index')
        ]);
    }

    /**
     * Create a new client
     * @param \Illuminate\Http\Request $request
     * @return array|mixed|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //validation request
        $this->validation->make($request->all(), [
            'name' => 'required|max:191',
            'redirect' => ['required', $this->redirectRule],
            'confidential' => [new BooleanRule()],
        ])->validate();

        //create a new client
        $client = $this->clients->create(
            $request->user()->getAuthIdentifier(),
            $request->name,
            $request->redirect,
            null,
            false,
            false,
            (bool) $request->confidential
        );

        if (Passport::$hashesClientSecrets) {
            return ['plainSecret' => $client->plainSecret] + $client->toArray();
        }

        $client->makeVisible('secret');

        return $this->message(__("Client created successfully"), 201);
    }


    /**
     * Update clients
     * @param \Illuminate\Http\Request $request
     * @param mixed $clientId
     * @return mixed|Response|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $clientId)
    {
        $client = $this->clients->findForUser($clientId, $request->user()->getAuthIdentifier());

        if (!$client) {
            return new Response('', 404);
        }

        $this->validation->make($request->all(), [
            'name' => 'required|max:191',
            'redirect' => ['required', $this->redirectRule],
        ])->validate();

        $this->clients->update(
            $client,
            $request->name,
            $request->redirect
        );

        return $this->message(__('Client updated successfully'), 201);
    }

    /**
     * destroy 
     * @param \Illuminate\Http\Request $request
     * @param mixed $clientId
     * @return ReportError|Response
     */
    public function destroy(Request $request, $clientId)
    {
        $client = $this->clients->findForUser($clientId, $request->user()->getAuthIdentifier());
        if (!$client) {
            throw new ReportError(__("Client not found"), 404);
        }

        $this->clients->delete($client);

        return $this->message(__("Client deleted successfully"), 200);
    }
}
