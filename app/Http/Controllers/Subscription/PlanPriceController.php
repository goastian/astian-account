<?php
namespace App\Http\Controllers\Subscription;

use Illuminate\Http\Request;
use App\Models\Subscription\Plan;
use App\Models\Subscription\Price;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\GlobalController;

class PlanPriceController extends GlobalController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:administrator_plan_full,administrator_plan_create')->only('store');
        $this->middleware('scope:administrator_plan_full,administrator_plan_destroy')->only('destroy');
    }

    /**
     * add price
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subscription\Plan $plan
     * @param \App\Models\Subscription\Price $price
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Plan $plan, Price $price)
    {
        $request->validate([
            'billing_period' => [
                'required',
                function ($attribute, $value, $fail) use ($price) {
                    if (empty($value)) {
                        $fail(__('The :attribute is required', ['attribute' => $attribute]));
                    }

                    $period = $price->billingPeriod();
                    if (!isset($period[$value])) {
                        $fail(__("The values available are (:period)", ['period' => implode(', ', array_keys($period))]));
                    }
                },
            ],
            'amount' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!empty($value) && !is_numeric($value)) {
                        $fail(__('The :attribute is not a number', ['attribute' => $attribute]));
                    }
                }
            ]
        ]);

        DB::transaction(function () use ($request, $plan) {

            $plan->prices()->create([
                'amount' => $request->amount,
                'billing_period' => $request->billing_period
            ]);
        });

        return $this->message(__('Price has been added successfully'), 201);
    }


    /**
     * destroy price
     * @param \App\Models\Subscription\Plan $plan
     * @param \App\Models\Subscription\Price $price
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Plan $plan, Price $price)
    {
        $price->delete();

        return $this->message(__('Price has been deleted successfully'), 200);
    }
}
