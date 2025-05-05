<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\GlobalController;
use Illuminate\Http\Request;
use App\Models\Subscription\Plan;
use App\Models\Subscription\Scope;

class PlanScopeController extends GlobalController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:administrator_plan_full,administrator_plan_revoke')->only('revoke');
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
