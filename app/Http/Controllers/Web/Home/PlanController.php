<?php
namespace App\Http\Controllers\Web\Home;

use Inertia\Inertia;
use App\Models\Subscription\Plan;
use App\Http\Controllers\WebController;

class PlanController extends WebController
{

    public function __construct()
    {

    }

    public function index(Plan $plan)
    {
        $data = $plan->query();

        $data = $data->where('active', true)->where('public', true);

        $params = $this->filter_transform($plan->transformer);

        $data = $this->searchByBuilder($data, $params);

        $data = $this->orderByBuilder($data, $plan->transformer);

        if (request()->wantsJson()) {
            $data = $this->showAllByBuilder($data, $plan->transformer);
        }

        return Inertia::render('Resources/Plan', [
            'plans' => $this->showAllByBuilderArray($data, $plan->transformer),
            'user' => $this->authenticated_user()
        ]);
    }
}
