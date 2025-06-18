<?php

namespace App\Http\Controllers\Web\Admin\Subscription;

use App\Http\Requests\ServiceScope\StoreRequest;
use App\Repositories\ServiceRepository;
use App\Rules\BooleanRule;
use Illuminate\Http\Request;
use App\Models\Subscription\Scope;
use Illuminate\Support\Facades\DB;
use App\Models\Subscription\Service;
use App\Http\Controllers\WebController;
use Elyerr\ApiResponse\Exceptions\ReportError;
use App\Transformers\Subscription\ServiceScopeTransformer;

class ServiceScopeController extends WebController
{
    /**
     * Service repository
     * @var ServiceRepository
     */
    public $repository;

    /**
     * Construct 
     */
    public function __construct(ServiceRepository $serviceRepository)
    {
        parent::__construct();
        $this->repository = $serviceRepository;
        $this->middleware('userCanAny:administrator_service_full,administrator_service_view')->only('index');
        $this->middleware('userCanAny:administrator_service_full,administrator_service_assign')->only('assign');
        $this->middleware('userCanAny:administrator_service_full,administrator_service_revoke')->only('revoke');
        $this->middleware('wants.json')->only('index');
    }

    /**
     * Show the all scope of the service
     * @param \App\Models\Subscription\Service $service
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Service $service)
    {
        return $this->repository->searchScopes($service->id);
    }

    /**
     * Add scopes
     * @param \App\Http\Requests\ServiceScope\StoreRequest $request
     * @param \App\Models\Subscription\Service $service
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assign(StoreRequest $request, Service $service)
    {
        return $this->repository->assignOrUpdateScopes($service->id, $request->toArray());
    }

    /**
     * Revoke scope
     * @param \App\Models\Subscription\Service $service
     * @param \App\Models\Subscription\Scope $scope
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function revoke(Service $service, Scope $scope)
    {
        return $this->repository->revokeScope($service->id, $scope->id);
    }
}
