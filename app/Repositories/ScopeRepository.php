<?php
namespace App\Repositories;

use Elyerr\ApiResponse\Assets\JsonResponser;
use Illuminate\Http\Request;
use App\Models\Subscription\Scope;
use App\Repositories\Contracts\Contracts;


class ScopeRepository implements Contracts
{

    use JsonResponser;

    /**
     * Model
     * @var Scope
     */
    public $model;

    /**
     * Construct
     * @param \App\Models\Subscription\Scope $scope
     */
    public function __construct(Scope $scope)
    {
        $this->model = $scope;
    }

    /**
     * Search resources
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function search(Request $request)
    {
        // Create query
        $data = $this->model->query();

        // Apply eager loading and filter by active and public
        $data = $data->with([
            'role',
            'service',
            'service.group'
        ])->where('active', true)
            ->where('public', false);

        // search by role name or slug
        if ($request->has('role')) {
            $data->whereHas('role', function ($query) use ($request) {
                return $query
                    ->where('name', 'like', "%" . $request->role . "%")
                    ->orWhere('slug', 'like', "%" . $request->role . "%");
            });
        }

        // Search by service name or slug
        if ($request->has('service')) {
            $data->whereHas('service', function ($query) use ($request) {
                return $query
                    ->where('name', 'like', "%" . $request->service . "%")
                    ->orWhere('slug', 'like', "%" . $request->service . "%");
            });
        }

        return $this->showAllByBuilder($data, $this->model->transformer);
    }

    /**
     * Create new resource
     * @param array $data
     * @return void
     */
    public function create(array $data)
    {

    }

    /**
     * Search specific resource
     * @param string $id
     * @return void
     */
    public function find(string $id)
    {

    }

    /**
     * Update specific resource
     * @param string $id
     * @param array $data
     * @return void
     */
    public function update(string $id, array $data)
    {

    }

    /**
     * Delete specific resource
     * @param string $id 
     * @return void
     */
    public function delete(string $id)
    {

    }

    /**
     * Search scope by service
     * @param string $scope_id
     * @param string $service_id
     * @return object|Scope|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null
     */
    public function searchScopeByService(string $scope_id, string $service_id)
    {
        return $this->model->where('id', $scope_id)
            ->whereHas('service', function ($query) use ($service_id) {
                $query->where('id', $service_id);
            })->first();
    }
}
