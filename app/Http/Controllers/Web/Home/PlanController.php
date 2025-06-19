<?php
namespace App\Http\Controllers\Web\Home;

use App\Repositories\PlanRepository;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Subscription\Plan;
use App\Http\Controllers\WebController;

class PlanController extends WebController
{
    /**
     * Plan repository
     * @var 
     */
    public $repository;

    /**
     * Construct
     * @param \App\Repositories\PlanRepository $planRepository
     */
    public function __construct(PlanRepository $planRepository)
    {
        $this->repository = $planRepository;
    }

    /**
     * Show the all resources for guest users
     * @param \Illuminate\Http\Request $request 
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return $this->repository->searchPlanForGuest($request);
        }

        return Inertia::render('Resources/Plan', [
            'user' => $this->authenticated_user(),
            'route' => route('plans.index'),
        ]);
    }

    public function pay(Request $request, plan $plan) {
        //Prepare query
        $data = $plan->query();

        // Search plans only active an public
        $data = $data->with(['scopes', 'prices'])
            ->where('active', true);

        // Search by billing period
        if (!empty($billing_period = $request->billing_period)) {
            $data->whereHas('prices', function ($query) use ($billing_period) {
                $query->where('billing_period', $billing_period);
            });
        }

        // Search by service like cloud , vpn , etc
        if (!empty($service_name = $request->service)) {
            $data->whereHas('scopes.service', function ($query) use ($service_name) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($service_name) . '%']);
            });
        }

        $params = $this->filter_transform($plan->transformer);

        $this->searchByBuilder($data, $params);

        $this->orderByBuilder($data, $plan->transformer);

        if (request()->wantsJson()) {
            return $this->showAllByBuilder($data, $plan->transformer);
        }

        return Inertia::render('Resources/Pay', [
            'user' => $this->authenticated_user(),
            'route' => route('pay.pay')
        ]);
    }
}
