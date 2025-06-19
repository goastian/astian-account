<?php
namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Subscription\Group;
use Elyerr\ApiResponse\Assets\Asset;
use App\Repositories\Contracts\Contracts;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Elyerr\ApiResponse\Exceptions\ReportError;

class GroupRepository implements Contracts
{

    use JsonResponser, Asset;

    /**
     * Instance of group model
     * @var Group
     */
    public $model;

    /**
     * 
     * @param \App\Models\Subscription\Group $group
     */
    public function __construct(Group $group)
    {
        $this->model = $group;
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

        $this->orderByBuilder($data, $this->model->transformer);

        return $this->showAllByBuilder($data, $this->model->transformer);
    }

    /**
     * Create new group
     * @param array $data
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function create(array $data)
    {
        $model = $this->model->create([
            'name' => $data['name'],
            'slug' => $this->slug($data['name']),
            'description' => $data['description'],
            'system' => $data['system'],
        ]);

        return $this->showOne($model, $this->model->transformer, 201);
    }

    /**
     * Search specific resource
     * @param string $id
     * @return Group
     */
    public function find(string $id)
    {
        return $this->model->find($id);
    }

    /**
     * Show group details
     * @param string $group_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function detail(string $group_id)
    {
        $model = $this->find($group_id);

        return $this->showOne($model, $this->model->transformer);
    }

    /**
     * Update specific resource
     * @param string $id
     * @param array $data
     * @return JsonResponser
     */
    public function update(string $group_id, array $data)
    {
        $model = $this->find($group_id);

        if (!empty($data['description']) && $model->description != $data['description']) {
            $model->description = $data["description"];
            $model->push();
        }

        return $this->showOne($model, $this->model->transformer, 201);
    }

    /**
     * Delete specific resource
     * @param string $id 
     * @return JsonResponser
     */
    public function delete(string $group_id)
    {
        $model = $this->find($group_id);

        if ($model->services()->count() === 0 && $model->users()->count()) {
            new ReportError(__("This action cannot be completed because this group is currently in use by another resource."), 403);
        }

        throw_if($model->system, new ReportError(__("This group cannot be deleted because it is a system group."), 403));

        collect(Group::groupByDefault())->map(function ($value, $key) use ($model) {
            throw_if($value->name == $model->name, new ReportError(__("This group cannot be deleted because it is a system group."), 403));
        });

        $model->delete();

        return $this->showOne($model, $this->model->transformer);
    }
}
