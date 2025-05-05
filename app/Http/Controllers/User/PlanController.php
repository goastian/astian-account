<?php
namespace App\Http\Controllers\User;

use App\Models\Subscription\Plan;
use App\Http\Controllers\Controller;

class PlanController extends Controller
{
    public function index(Plan $plan)
    {
        $data = $plan->query();

        $data = $data->where('active', true)->where('public', true);

        $params = $this->filter_transform($plan->transformer);

        $data = $this->searchByBuilder($data, $params);

        $data = $this->orderByBuilder($data, $plan->transformer);

        return $this->showAllByBuilder($data, $plan->transformer);
    }
}
