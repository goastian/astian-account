<?php
namespace App\Repositories\OAuth;

use Exception;
use Illuminate\Support\Str;
use App\Models\OAuth\Client;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use Elyerr\ApiResponse\Assets\JsonResponser;
use App\Transformers\OAuth\ClientTransformer;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Contracts\Auth\Authenticatable;
use Symfony\Component\HttpFoundation\Response; 

class ClientRepository extends \OpenIDConnect\Repositories\ClientRepository
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
     * Store a new authorization code grant client.
     *
     * @param  string[]  $redirectUris
     * @param  \Laravel\Passport\Contracts\OAuthenticatable|null  $user
     */
    public function createAuthorizationCodeGrantClient(
        string $name,
        array $redirectUris,
        bool $confidential = true,
        ?Authenticatable $user = null,
        bool $enableDeviceFlow = false,
        bool $private = true
    ): Client {
        $grantTypes = ['authorization_code', 'refresh_token'];

        if ($enableDeviceFlow) {
            $grantTypes[] = 'urn:ietf:params:oauth:grant-type:device_code';
        }

        return $this->create(
            $name,
            $grantTypes,
            $redirectUris,
            null,
            $confidential,
            $user,
            $private
        );
    }


    /**
     * Create new client
     * @param string $name
     * @param array $grantTypes
     * @param array $redirectUris
     * @param mixed $provider
     * @param bool $confidential
     * @param mixed $user
     * @param bool $private
     * @return Client
     */
    protected function create(
        string $name,
        array $grantTypes,
        array $redirectUris = [],
        ?string $provider = null,
        bool $confidential = true,
        ?Authenticatable $user = null,
        bool $private = true,
    ): Client {
        $client = Passport::client();
        $columns = $client->getConnection()->getSchemaBuilder()->getColumnListing($client->getTable());

        $attributes = [
            'name' => $name,
            'secret' => $confidential ? Str::random(40) : null,
            'provider' => $provider,
            'private' => $private,
            'revoked' => false,
            ...(in_array('redirect_uris', $columns) ? [
                'redirect_uris' => $redirectUris,
            ] : [
                'redirect' => implode(',', $redirectUris),
            ]),
            ...(in_array('grant_types', $columns) ? [
                'grant_types' => $grantTypes,
            ] : [
                'personal_access_client' => in_array('personal_access', $grantTypes),
                'password_client' => in_array('password', $grantTypes),
            ]),
        ];

        return match (true) {
            !is_null($user) && in_array('user_id', $columns) => $user->clients()->forceCreate($attributes),
            !is_null($user) => $user->oauthApps()->forceCreate($attributes),
            default => $client->newQuery()->forceCreate($attributes),
        };
    }

    /**
     * Find clients for users
     * @param string|int $clientId
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @return string|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\MorphMany<\Laravel\Passport\Client, \Illuminate\Foundation\Auth\User>|\Illuminate\Database\Eloquent\Relations\MorphMany<\Laravel\Passport\Client, \Illuminate\Foundation\Auth\User>[]|null
     */
    public function findForUser(string|int $clientId, Authenticatable $user): ?Client
    {
        return $user->oauthApps()
            ->where('revoked', false)
            ->where('private', false)
            ->find($clientId);
    }

    /**
     * Get the all clients for user
     * @param \Illuminate\Http\Request $request
     */
    public function findClientsForUser(Request $request)
    {
        $user = $request->user();
        $clients = $user->oauthApps()
            ->where('revoked', false)
            ->where('private', false)
            ->orderBy('name')->get();

        return $this->showAll($clients, ClientTransformer::class);
    }

    /**
     * Create clients for user
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\OAuth\Client
     */
    public function createClientForUser(Request $request): Client
    {
        $client = $this->createAuthorizationCodeGrantClient(
            $request->name,
            array_map('trim', explode(',', $request->redirect)),
            (bool) $request->input('confidential', true),
            auth()->user(),
            false,
            false
        );

        $client->secret = $client->plainSecret;
        $client->openid_connect_configuration = route('openid.discovery');

        return $client->makeVisible('secret');
    }

    /**
     * Update the given client.
     */
    public function updateClientForUser(Request $request, string|int $clientId): Response|Client
    {
        $user = $request->user();

        $client = $user->oauthApps()->where('revoked', false)->find($clientId);

        if (!$client) {
            return new Response('', 404);
        }

        $this->update(
            $client,
            $request->name,
            explode(',', $request->redirect)
        );

        return $client;
    }

    /**
     * Delete client for 
     * @param \Illuminate\Http\Request $request
     * @param string|int $clientId
     * @return Response|string|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\MorphMany<\Laravel\Passport\Client, \Illuminate\Foundation\Auth\User>|\Illuminate\Database\Eloquent\Relations\MorphMany<\Laravel\Passport\Client, \Illuminate\Foundation\Auth\User>[]
     */
    public function deleteClientForUser(Request $request, string|int $clientId)
    {
        $client = $this->findForUser($clientId, auth()->user());

        if (!$client) {
            throw new ReportError(__('Resource not found'), 404);
        }

        $client->delete();

        return $this->showOne($client, ClientTransformer::class);
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

        // Filter entries by private field
        $data->where('private', true);

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
        $client = $this->createAuthorizationCodeGrantClient(
            $data['name'],
            array_map('trim', explode(',', $data['redirect'])),
            (bool) $data['confidential'] ?? false,
            auth()->user(),
            false,
            true
        );

        $client->secret = $client->plainSecret;

        $client->openid_connect_configuration = route('openid.discovery');

        return $this->showOne($client->makeVisible('secret'), $this->model->transformer, 201);
    }

    /**
     * Find client for admin
     * @param string $client_id
     * @return Client|object|\Illuminate\Database\Eloquent\Model|null
     */
    public function findClientForAdmin(string $client_id)
    {
        return $this->model
            ->where('id', $client_id)
            ->where('private', true)
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
        $client = $this->findClientForAdmin($client_id);

        $this->update(
            $client,
            $data['name'],
            explode(',', $data['redirect'])
        );

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

    /**
     * Create personal access grant client
     * @param string $name
     * @param mixed $provider
     * @return Client
     */
    public function createPersonalAccessGrantClient(string $name, ?string $provider = null): Client
    {
        return $this->create(
            $name,
            ['personal_access'],
            [],
            $provider,
            true,
            auth()->user()
        );
    }

}
