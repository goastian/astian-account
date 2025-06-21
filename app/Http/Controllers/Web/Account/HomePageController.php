<?php
namespace App\Http\Controllers\Web\Account;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use App\Repositories\DashboardRepository;

class HomePageController extends WebController
{
    /**
     * Repository
     * @var \App\Repositories\DashboardRepository
     */
    public $repository;

    public function __construct(DashboardRepository $dashboardRepository)
    {
        $this->repository = $dashboardRepository;
    }

    /**
     * Show the dashboard
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser|\Inertia\Response
     */
    public function dashboard(Request $request)
    {
        if ($request->wantsJson()) {
            return $this->repository->home($request);
        }

        return Inertia::render("Account/About", [
            'route' => route('users.dashboard')
        ]);
    }
}
