<?php
namespace App\Http\Controllers\Web\Admin\Subscription;

use App\Models\Subscription\Plan;
use App\Models\Subscription\Price;
use App\Http\Controllers\WebController;
use App\Repositories\PlanRepository;

class PlanPriceController extends WebController
{
    /**
     * Repository
     * @var PlanRepository
     */
    public $repository;

    public function __construct(PlanRepository $planRepository)
    {
        parent::__construct();
        $this->repository = $planRepository;
        $this->middleware('userCanAny:administrator:plan:full,administrator:plan:destroy')->only('destroy');
    }

    /**
     * destroy price
     * @param \App\Models\Subscription\Plan $plan
     * @param \App\Models\Subscription\Price $price
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Plan $plan, Price $price)
    {
        return $this->repository->deletePrice($plan->id, $price->id);
    }
}
