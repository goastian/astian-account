<?php

namespace App\Http\Controllers\Web\Subscription;

use App\Http\Controllers\ApiController;
use App\Models\Subscription\Plan;
use App\Models\Subscription\Scope;

class PlanScopeController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator_plan_full,administrator_plan_revoke')->only('revoke');
    }

    /**
     * Detach scopes 
     * @param \App\Models\Subscription\Plan $plan
     * @param \App\Models\Subscription\Scope $scope
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function revoke(Plan $plan, Scope $scope)
    {
        $plan->scopes()->detach($scope->id);

        return $this->message(__('Scopes revoked successfully'), 200);
    }
}
