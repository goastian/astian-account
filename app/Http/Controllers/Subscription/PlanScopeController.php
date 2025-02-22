<?php

namespace App\Http\Controllers\Subscription;

use Illuminate\Http\Request;
use App\Models\Subscription\Plan;
use App\Http\Controllers\GlobalController;


class PlanScopeController extends GlobalController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:administrator_plan_full,administrator_plan_assign')->only('assign');
        $this->middleware('scope:administrator_plan_full,administrator_plan_revoke')->only('revoke');
    }

    /**
     * Assign scopes to the plan
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subscription\Plan $plan
     * @return void
     */
    public function assign(Request $request, Plan $plan)
    {
        $this->validate($request, [
            'scopes' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!is_array($value)) {
                        $fail(__("The :attribute must be an array", ["attribute" => $attribute]));
                    }
                }
            ]
        ]);

        $plan->scopes()->syncWithoutDetaching($request->scopes);

        return $this->message(__('Scopes assigned successfully'), 201);
    }

    /**
     * Detach scopes
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subscription\Plan $plan
     * @return void
     */
    public function revoke(Request $request, Plan $plan)
    {
        $this->validate($request, [
            'scopes' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!is_array($value)) {
                        $fail(__("The :attribute must be an array", ["attribute" => $attribute]));
                    }
                }
            ]
        ]);

        $plan->scopes()->detach($request->scopes);

        return $this->message(__('Scopes revoked successfully'), 201);
    }
}
