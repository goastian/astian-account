<?php
namespace App\Http\Controllers\Web\Admin\Subscription;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Subscription\Group;
use App\Repositories\GroupRepository;
use App\Http\Controllers\WebController;
use App\Http\Requests\Group\StoreRequest;

class GroupController extends WebController
{

    /**
     * Repository
     * @var GroupRepository
     */
    public $repository;

    public function __construct(GroupRepository $groupRepository)
    {
        parent::__construct();
        $this->repository = $groupRepository;
        $this->middleware('userCanAny:administrator_group_full,administrator_group_view')->only('index');
        $this->middleware('userCanAny:administrator_group_full,administrator_group_show')->only('show');
        $this->middleware('userCanAny:administrator_group_full,administrator_group_create')->only('store');
        $this->middleware('userCanAny:administrator_group_full,administrator_group_update')->only('update');
        $this->middleware('userCanAny:administrator_group_full,administrator_group_destroy')->only('destroy');
        $this->middleware('userCanAny:administrator_group_full,administrator_group_enable')->only('enable');
        $this->middleware('userCanAny:administrator_group_full,administrator_group_disable')->only('disable');
        $this->middleware('wants.json')->only('show');
    }

    /**
     * Show resources
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser|\Inertia\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return $this->repository->search($request);
        }

        return Inertia::render(
            "Admin/Groups/Index",
            [
                "route" => route('admin.groups.index')
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param \App\Http\Requests\Group\StoreRequest $request 
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        return $this->repository->create($request->toArray());
    }

    /**
     * Display the specified resource.
     * @param \App\Models\Subscription\Group $group
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Group $group)
    {
        return $this->repository->detail($group->id);
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subscription\Group $group
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function update(Request $request, Group $group)
    {
        $this->validate($request, [
            'description' => ['nullable', 'max:200'],
        ]);

        return $this->repository->update($group->id, $request->toArray());
    }

    /**
     * Destroy specific resource
     * @param \App\Models\Subscription\Group $group
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function destroy(Group $group)
    {
        return $this->repository->delete($group->id);
    }
}
