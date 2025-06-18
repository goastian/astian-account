<?php
namespace App\Repositories\OAuth;

use Exception;
use App\Models\OAuth\Client;
use Illuminate\Http\Request;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Laravel\Passport\ClientRepository as Repository;

class ClientRepository extends Repository
{

    use JsonResponser;

    /**
     * Client Model
     * @var Client
     */
    public $model;

    /**
     * Construct
     * @param \App\Models\OAuth\Client $client
     */
    public function __construct(Client $client)
    {
        $this->model = $client;
    }

    /**
     * Retrieve the all clients for admin users
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function searchClientsForAdmin(Request $request)
    {
        // Retrieve params of the request
        $params = $this->filter_transform($this->model->transformer);

        // Prepare query
        $data = $this->model->query();

        // Filter entries without a user ID and that do not belong to a personal access client
        $data = $data->whereNull('user_id')->where('personal_access_client', false);

        // Search 
        $data = $this->searchByBuilder($data, $params);

        // Order by
        $data = $this->orderByBuilder($data, $this->model->transformer);

        return $this->showAllByBuilder($data, $this->model->transformer);
    }

    /**
     * Create clients for admins  
     * @param array $data
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function createClientForAdmin(array $data)
    {
        $client = $this->create(
            null,
            $data['name'],
            $data['redirect'],
            null,
            false,
            false,
            (bool) $data['confidential']
        );

        return $this->showOne($client, $this->model->transformer, 201);
    }

    /**
     * Find client for admin
     * @param string $client_id
     * @return Client|object|\Illuminate\Database\Eloquent\Model|null
     */
    public function findClientForAdmin(string $client_id)
    {
        return $this->model->where('id', $client_id)
            ->where('personal_access_client', false)
            ->first();
    }

    /**
     * Retrieve the client detail for admins
     * @param string $client_id
     * @throws \Exception
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function retrieveClientForAdmin(string $client_id)
    {
        $client = $this->findClientForAdmin($client_id);

        if (empty($client)) {
            throw new Exception(__('Not found'), 404);
        }

        return $this->showOne($client, $client->transformer);
    }

    /**
     * Update client for admin user
     * @param string $client_id
     * @param array $data
     * @throws \Exception
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function updateClientForAdmin(string $client_id, array $data)
    {
        $client = $client = $this->findClientForAdmin($client_id);

        if (empty($client)) {
            throw new Exception(__('Not found'), 404);
        }

        if (!empty($data['name']) && $client->name != $data['name']) {
            $client->name = $data['name'];
        }

        if (!empty($data['redirect']) && $client->redirect != $data['redirect']) {
            $client->redirect = $data['redirect'];
        }

        if (!empty($data['revoked']) && $client->revoked != $data['revoked']) {
            $client->revoked = $data['revoked'];

            //revoke the all credentials for this client
            $client->tokens()->update(['revoked' => true]);
        }

        $client->push();

        return $this->showOne($client, $client->transformer);
    }

    /**
     * Revoke client for admin
     * @param string $client_id
     * @throws \Exception
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function revokeClientForAdmin(string $client_id)
    {
        $client = $client = $this->findClientForAdmin($client_id);

        if (empty($client)) {
            throw new Exception(__('Not found'), 404);
        }

        $client->tokens()->update(['revoked' => true]);

        $client->delete();

        return $this->showOne($client, $client->transformer);
    }
}
