<?php

namespace App\Http\Controllers\Web\Admin\Subscription;
 
use App\Models\Subscription\Plan;
use App\Models\Subscription\Scope;
use App\Http\Controllers\WebController;

class PlanScopeController extends WebController
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
