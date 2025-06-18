<?php
namespace App\Http\Controllers\Web\Admin\Subscription;

use App\Http\Requests\Plan\StoreRequest;
use App\Http\Requests\Plan\UpdateRequest;
use App\Repositories\PlanRepository;
use Inertia\Inertia;
use App\Rules\BooleanRule;
use Illuminate\Http\Request;
use App\Models\Subscription\Plan;
use App\Models\Subscription\Scope;
use Illuminate\Support\Facades\DB;
use Stevebauman\Purify\Facades\Purify;
use App\Http\Controllers\WebController;

class PlanController extends WebController
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
        $this->middleware('userCanAny:administrator_plan_full,administrator_plan_view')->only('index');
        $this->middleware('userCanAny:administrator_plan_full,administrator_plan_show')->only('show');
        $this->middleware('userCanAny:administrator_plan_full,administrator_plan_create')->only('store');
        $this->middleware('userCanAny:administrator_plan_full,administrator_plan_update')->only('update');
        $this->middleware('userCanAny:administrator_plan_full,administrator_plan_destroy')->only('destroy');
        $this->middleware('wants.json')->only('show');
    }

    /**
     * Show resources
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser|\Inertia\Response
     */
    public function index(Request $request)
    {
        if (request()->wantsJson()) {
            return $this->repository->search($request);
        }

        return Inertia::render("Admin/Plans/Index", [
            'route' => [
                'services' => route("admin.services.index"),
                'plans' => route('admin.plans.index')
            ]
        ]);
    }

    /**
     * Create new plan
     * @param \App\Http\Requests\Plan\StoreRequest $request
     * @param \App\Models\Subscription\Plan $plan
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function store(StoreRequest $request, Plan $plan)
    {
        return $this->repository->create($request->toArray());
    }

    /**
     * Show details of the plan
     * @param \App\Models\Subscription\Plan $plan
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function show(Plan $plan)
    {
        return $this->repository->details($plan->id);
    }

    /**
     * Update specific resource
     * @param \App\Http\Requests\Plan\UpdateRequest $request
     * @param \App\Models\Subscription\Plan $plan
     */
    public function update(UpdateRequest $request, Plan $plan)
    {
        return $this->repository->update($plan->id, $request->toArray());
    }

    /**
     * Delete specific resource
     * @param \App\Models\Subscription\Plan $plan
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function destroy(Plan $plan)
    {
        return $this->repository->delete($plan->id);
    }
}
