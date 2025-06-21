<?php

namespace App\Http\Controllers\Web\Admin\Subscription;

use App\Models\Subscription\Plan;
use App\Models\Subscription\Scope;
use App\Http\Controllers\WebController;
use App\Repositories\PlanRepository;

class PlanScopeController extends WebController
{

    /**
     * Repository
     * @var PlanRepository
     */
    public $repository;

    /**
     * Construct
     * @param \App\Repositories\PlanRepository $planRepository
     */
    public function __construct(PlanRepository $planRepository)
    {
        parent::__construct();
        $this->repository = $planRepository;
        $this->middleware('userCanAny:administrator_plan_full,administrator_plan_revoke')->only('revoke');
    }

    /**
     * Revoke scopes 
     * @param \App\Models\Subscription\Plan $plan
     * @param \App\Models\Subscription\Scope $scope
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function revoke(Plan $plan, Scope $scope)
    {
        return $this->repository->deleteScope($plan->id, $scope->id);
    }
}
