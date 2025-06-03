<?php
namespace App\Http\Controllers\Web\Admin\Subscription;

use App\Models\Subscription\Plan;
use App\Models\Subscription\Price;
use App\Http\Controllers\WebController;

class PlanPriceController extends WebController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator_plan_full,administrator_plan_destroy')->only('destroy');
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
