<?php
namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Subscription\Role;
use Elyerr\ApiResponse\Assets\Asset;
use App\Repositories\Contracts\Contracts;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Elyerr\ApiResponse\Exceptions\ReportError;


class RoleRepository implements Contracts
{
    use JsonResponser, Asset;

    /**
     * Model
     * @var Role
     */
    public $model;


    public function __construct(Role $role)
    {
        $this->model = $role;
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

        // Search
        $data = $this->searchByBuilder($data, $params);

        // Order by
        $data = $this->orderByBuilder($data, $this->model->transformer);

        return $this->showAllByBuilder($data, $this->model->transformer);
    }

    /**
     * Create new resource
     * @param array $data
     * @return JsonResponser
     */
    public function create(array $data)
    {
        $model = $this->model->create([
            'name' => $data['name'],
            'slug' => $this->slug($data['name']),
            'description' => $data['description'],
            'system' => $data['system'] ?? false,
        ]);
        //send event
        return $this->showOne($model, $this->model->transformer, 201);
    }

    /**
     * Search specific resource
     * @param string $id
     * @return Role
     */
    public function find(string $id)
    {
        return $this->model->find($id);
    }

    /**
     * Show role details
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function details(string $id)
    {
        $model = $this->find($id);

        return $this->showOne($model, $this->model->transformer);
    }

    /**
     * Update specific resource
     * @param string $id
     * @param array $data
     * @return JsonResponser
     */
    public function update(string $id, array $data)
    {
        $model = $this->model->find($id);

        if (!empty($data['name'])) {
            $model->name = $data['name'];
        }

        if (!empty($data['description'])) {
            $model->description = $data['description'];
        }

        $model->push();

        return $this->showOne($model, $this->model->transformer, 200);
    }

    /**
     * Delete specific resource
     * @param string $id 
     * @return JsonResponser
     */
    public function delete(string $id)
    {
        $model = $this->find($id);

        collect(Role::rolesByDefault())->map(function ($value, $key) use ($model) {
            throw_if($value->name == $model->name, new ReportError(__("This action cannot be completed because this role is a system role and cannot be deleted."), 403));
        });

        throw_if($model->system, new ReportError(__("This action cannot be completed because this role is a system role and cannot be deleted."), 403));

        throw_if($model->scopes()->count() > 0, new ReportError(__("This action cannot be completed because this role is currently assigned to one or more scopes and cannot be deleted."), 403));

        $model->delete();

        //send event
        return $this->showOne($model, $this->model->transformer);
    }
}
