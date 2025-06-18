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
}
