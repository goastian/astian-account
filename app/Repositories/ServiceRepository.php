<?php
namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Subscription\Service;
use Elyerr\ApiResponse\Assets\Asset;
use App\Repositories\Contracts\Contracts;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Elyerr\ApiResponse\Exceptions\ReportError;
use App\Transformers\Subscription\ServiceScopeTransformer;
use Illuminate\Database\UniqueConstraintViolationException;

class ServiceRepository implements Contracts
{

    use JsonResponser, Asset;

    /**
     * Model
     * @var Service
     */
    public $model;

    /**
     * Scope repository
     */
    public $scopeRepository;

    /**
     * Construct
     * @param \App\Models\Subscription\Service $service
     */
    public function __construct(Service $service, ScopeRepository $scopeRepository)
    {
        $this->model = $service;
        $this->scopeRepository = $scopeRepository;
    }

    /**
     * Search resources
     * @param \Illuminate\Http\Request $request
     * @return JsonResponser
     */
    public function search(Request $request)
    {
        // Retrieve params of the request
        $params = $this->filter_transform($this->model->transformer);

        // Prepare query
        $data = $this->model->query();

        // Eager loading
        $data = $data->with(['group', 'scopes', 'scopes.role']);

        if ($request->group) {
            $data->whereHas(
                'group',
                function ($query) use ($request) {
                    $query->whereRaw("LOWER(name) LIKE ?", ['%' . strtolower($request->group) . '%']);
                }
            );
        }

        // Filter by visibility
        if ($request->visibility) {
            $data->where('visibility', $request->visibility);
        }

        // Search data with the param request
        $data = $this->searchByBuilder($data, $params);

        // Order by
        $data = $this->orderByBuilder($data, $this->model->transformer);

        return $this->showAllByBuilder($data, $this->model->transformer);
    }

    /**
     * Create ne resource
     * @param array $data
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function create(array $data)
    {
        try {
            $service = $this->model->create([
                'name' => $data['name'],
                'slug' => $this->slug($data['name']),
                'description' => $data['description'],
                'group_id' => $data['group_id'],
                'system' => $data['system'] ?? false,
                'visibility' => $data['visibility']
            ]);

            return $this->showOne($service, $this->model->transformer, 201);

        } catch (UniqueConstraintViolationException $th) {
            throw new ReportError(__("This service cannot be registered, as it is already associated with this group."), 400);
        }
    }

    /**
     * Search specific resource
     * @param string $id
     * @return Service
     */
    public function find(string $id)
    {
        return $this->model->with(['group', 'scopes', 'scopes.role'])->find($id);
    }

    /**
     * Show service details
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function details(string $id)
    {
        $model = $this->find($id);

        return $this->showOne($model, $this->model->transformer, 200);
    }

    /**
     * Update specific resource
     * @param string $id
     * @param array $data
     * @return JsonResponser
     */
    public function update(string $id, array $data)
    {
        $service = $this->find($id);

        $update = false;

        if ($data['description'] && $service->description != $data['description']) {
            $update = true;
            $service->description = $data['description'];
        }

        if ($data['name'] && $service->name != $data['name']) {
            $update = true;
            $service->name = $data['name'];
        }

        if ($data['visibility'] && $service->visibility != $data['visibility']) {
            $update = true;
            $service->visibility = $data['visibility'];
        }

        if ($update) {
            $service->push();
        }

        return $this->showOne($service, $this->model->transformer);
    }

    /**
     * Delete specific resource
     * @param string $id 
     * @return JsonResponser
     */
    public function delete(string $id)
    {
        $model = $this->find($id);

        throw_if(
            $model->system,
            new ReportError(__("This action cannot be completed because this service is a system service and cannot be deleted."), 403)
        );

        throw_if(
            $model->scopes()->count() > 0,
            new ReportError(__("This action can't be done"), 400)
        );

        $model->delete();

        return $this->showOne($model, $this->model->transformer);
    }

    /**
     * Show the all scopes for service
     * @param string $service_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function searchScopes(string $service_id)
    {

        $model = $this->find($service_id);

        return $this->showAll($model->scopes, ServiceScopeTransformer::class);
    }

    /**
     * Assign or update scope
     * @param string $service_id
     * @param array $data
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assignOrUpdateScopes(string $service_id, array $data)
    {
        $this->model->find($service_id)
            ->scopes()->updateOrCreate(
                [
                    'role_id' => $data['role_id'],
                ],
                [
                    'role_id' => $data['role_id'],
                    'public' => $data['public'] ?? false,
                    'active' => $data['active'] ?? false,
                    'api_key' => $data['api_ke'] ?? false,
                ]
            );

        return $this->message(__('Role has been added successfully'), 201);
    }

    /**
     * Revoke scope 
     * @param string $service_id
     * @param string $scope_id
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function revokeScope(string $service_id, string $scope_id)
    {
        $scope = $this->scopeRepository->searchScopeByService($scope_id, $service_id);

        if (empty($scope)) {
            throw new ReportError(__("This action is invalid"), 400);
        }

        try {
            $scope->delete();
        } catch (\Throwable $th) {
            throw new ReportError(__("This resource cannot be deleted because it is being used by another resource."), 400);
        }

        return $this->message(__('Role has been revoked successfully'), 200);
    }
}
