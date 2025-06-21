<?php
namespace App\Http\Controllers\Web\Admin\Subscription;

use Inertia\Inertia; 
use Illuminate\Http\Request;
use App\Models\Subscription\Role; 
use App\Repositories\RoleRepository; 
use App\Http\Controllers\WebController; 
use App\Http\Requests\Role\StoreRequest;
use App\Http\Requests\Role\UpdateRequest;

class RoleController extends WebController
{

    /**
     * Repository
     * @var RoleRepository
     */
    public $repository;

    /**
     * Construct
     * @param \App\Repositories\RoleRepository $roleRepository
     */
    public function __construct(RoleRepository $roleRepository)
    {
        parent::__construct();
        $this->repository = $roleRepository;
        $this->middleware('userCanAny:administrator_role_full,administrator_role_view')->only('index');
        $this->middleware('userCanAny:administrator_role_full,administrator_role_show')->only('show');
        $this->middleware('userCanAny:administrator_role_full,administrator_role_create')->only('store');
        $this->middleware('userCanAny:administrator_role_full,administrator_role_update')->only('update');
        $this->middleware('userCanAny:administrator_role_full,administrator_role_destroy')->only('destroy');
        $this->middleware('wants.json')->only('show');
    }

    /**
     * Show the all resources
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser|\Inertia\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return $this->repository->search($request);
        }

        return Inertia::render("Admin/Role/Index", [
            'route' => route('admin.roles.index')
        ]);
    }

    /**
     * Show detail
     * @param \App\Models\Subscription\Role $role
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Role $role)
    {
        return $this->repository->details($role->id);
    }

    /**
     * Create new role
     * @param \App\Http\Requests\Role\StoreRequest $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function store(StoreRequest $request)
    {
        return $this->repository->create($request->toArray());
    }

    /**
     * Update resource
     * @param \App\Http\Requests\Role\UpdateRequest $request
     * @param \App\Models\Subscription\Role $role
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function update(UpdateRequest $request, Role $role)
    {
        return $this->repository->update($role->id, $request->toArray());
    }

    /**
     * Delete role
     * @param \App\Models\Subscription\Role $role
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function destroy(Role $role)
    {
        return $this->repository->delete($role->id);
    }
}
