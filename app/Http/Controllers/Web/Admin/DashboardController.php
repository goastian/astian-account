<?php
namespace App\Http\Controllers\Web\Admin;

use App\Repositories\DashboardRepository;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;

class DashboardController extends WebController
{

    /**
     * Dashboard repository
     * @var DashboardRepository
     */
    public $repository;

    public function __construct(DashboardRepository $dashboardRepository)
    {
        parent::__construct();
        $this->repository = $dashboardRepository;
        $this->middleware('userCanAny:administrator:admin:full,administrator:admin:dashboard');
    }

    public function dashboard(Request $request)
    {
        if ($request->wantsJson()) {
            return $this->repository->admin($request);
        }

        return Inertia::render("Admin/Dashboard/Index", [
            "route" => route("admin.dashboard")
        ]);
    }
}
