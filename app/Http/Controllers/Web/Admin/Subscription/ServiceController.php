<?php
namespace App\Http\Controllers\Web\Admin\Subscription;

use Inertia\Inertia; 
use Illuminate\Http\Request;   
use App\Models\Subscription\Service;
use App\Repositories\ServiceRepository;
use App\Http\Controllers\WebController; 
use App\Http\Requests\Service\StoreRequest;
use App\Http\Requests\Service\UpdateRequest;

class ServiceController extends WebController
{
    /**
     * Repository
     * @var ServiceRepository
     */
    public $repository;

    /**
     * Construct
     * @param \App\Repositories\ServiceRepository $serviceRepository
     */
    public function __construct(ServiceRepository $serviceRepository)
    {
        parent::__construct();
        $this->repository = $serviceRepository;
        $this->middleware('userCanAny:administrator_service_full,administrator_service_view')->only('index');
        $this->middleware('userCanAny:administrator_service_full,administrator_service_show')->only('show');
        $this->middleware('userCanAny:administrator_service_full,administrator_service_create')->only('store');
        $this->middleware('userCanAny:administrator_service_full,administrator_service_update')->only('update');
        $this->middleware('userCanAny:administrator_service_full,administrator_service_destroy')->only('destroy');
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

        // Render vue component
        return Inertia::render("Admin/Service/Index", [
            'route' => [
                'services' => route("admin.services.index"),
                'groups' => route("admin.groups.index"),
                'roles' => route("admin.roles.index")
            ]
        ]);
    }

    /**
     * Create resource
     * @param \App\Http\Requests\Service\StoreRequest $request 
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        return $this->repository->create($request->toArray());
    }

    /**
     * Show details
     * @param \App\Models\Subscription\Service $service
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Service $service)
    {
        return $this->repository->details($service->id);
    }

    /**
     * Update resource
     * @param \App\Http\Requests\Service\UpdateRequest $request
     * @param \App\Models\Subscription\Service $service
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function update(UpdateRequest $request, Service $service)
    {
        return $this->repository->update($service->id, $request->toArray());
    }

    /**
     * Destroy specific resource
     * @param \App\Models\Subscription\Service $service
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function destroy(Service $service)
    {
        return $this->repository->delete($service->id);
    }
}
