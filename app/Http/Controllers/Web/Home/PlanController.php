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
        $data = $plan->query();
        $data = $data->with(['scopes', 'prices'])
            ->where('active', true)
            ->where('public', true);

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
            $data = $this->showAllByBuilder($data, $plan->transformer);
        }

        return Inertia::render('Resources/Plan', [
            'plans' => $this->showAllByBuilderArray($data, $plan->transformer),
            'user' => $this->authenticated_user()
        ]);
    }
}
