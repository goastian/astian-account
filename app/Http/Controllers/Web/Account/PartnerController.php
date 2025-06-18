<?php
namespace App\Http\Controllers\Web\Account;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use App\Repositories\PartnerRepository;
use App\Repositories\DashboardRepository;

class PartnerController extends WebController
{

    /**
     * Dashboard repository
     * @var DashboardRepository
     */
    public $dashboardRepository;

    /**
     * User repository
     * @var PartnerRepository
     */
    public $repository;

    public function __construct(
        DashboardRepository $dashboardRepository,
        PartnerRepository $partnerRepository
    ) {
        $this->dashboardRepository = $dashboardRepository;
        $this->repository = $partnerRepository;
        $this->middleware("userCanAny:reseller_partner_full");
    }

    /**
     * Summary of dashboard
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subscription\Transaction $transaction
     * @return \Inertia\Response | array
     */
    public function dashboard(Request $request)
    {
        $meta = $this->dashboardRepository->partner($request);

        if ($request->wantsJson()) {
            return $meta;
        }

        return Inertia::render("Partner/Index", [
            "sales" => $meta,
            "route" => route("partners.dashboard")
        ]);
    }


    /**
     * Show referral link
     * @return \Inertia\Response
     */
    public function show()
    {
        $partner = $this->repository->details(auth()->user()->id);

        return Inertia::render("Partner/Refer", [
            "partner" => $partner,
            "route" => route('partners.generate'),
        ]);
    }

    /**
     * Generate partner
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function generate()
    {
        return $this->repository->generateLink();
    }

    /**
     * Show the all transactions 
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser|\Inertia\Response
     */
    public function sales(Request $request)
    {
        if ($request->wantsJson()) {
            return $this->repository->search($request);
        }

        return Inertia::render("Partner/Sales", [
            "route" => route("partners.sales")
        ]);
    }
}
