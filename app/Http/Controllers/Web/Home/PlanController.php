<?php
namespace App\Http\Controllers\Web\Home;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Subscription\Plan;
use App\Http\Controllers\WebController;

class PlanController extends WebController
{

    public function __construct()
    {

    }

    public function index(Request $request, Plan $plan)
    {
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

        return Inertia::render('Resources/Plan', [
            'user' => $this->authenticated_user(),
            'route' => route('plans.index'),
        ]);
    }
}
