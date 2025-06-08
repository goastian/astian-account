<?php
namespace App\Http\Controllers\Web\Admin\Subscription;

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

    public function __construct()
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator_plan_full,administrator_plan_view')->only('index');
        $this->middleware('userCanAny:administrator_plan_full,administrator_plan_show')->only('show');
        $this->middleware('userCanAny:administrator_plan_full,administrator_plan_create')->only('store');
        $this->middleware('userCanAny:administrator_plan_full,administrator_plan_update')->only('update');
        $this->middleware('userCanAny:administrator_plan_full,administrator_plan_destroy')->only('destroy');

        $this->middleware('wants.json')->only('show');
    }

    /**
     * Show the plan resources
     * @param \App\Models\Subscription\Plan $plan
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Plan $plan)
    {
        $data = $plan->query();

        $params = $this->filter_transform($plan->transformer);

        $data = $this->searchByBuilder($data, $params);

        $data = $this->orderByBuilder($data, $plan->transformer);
        if (request()->wantsJson()) {
            return $this->showAllByBuilder($data, $plan->transformer);
        }

        return Inertia::render("Admin/Plans/Index", [
            'plans' => $this->showAllByBuilderArray($data, $plan->transformer),
            'route' => [
                'services' => route("admin.services.index"),
                'plans' => route('admin.plans.index')
            ]
        ]);
    }

    /**
     * create a new resource
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subscription\Plan $plan
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Plan $plan)
    {
        $this->validate($request, [
            'name' => [
                'required',
                'max:150',
                function ($attribute, $value, $fail) use ($plan) {
                    $plan = $plan->where('slug', $this->slug($value))->first();

                    if (!empty($plan)) {
                        $fail(__("This plan has been registered"));
                    }

                }
            ],
            'description' => ['required'],
            'active' => ['required', new BooleanRule()],
            'trial_enabled' => ['nullable', new BooleanRule()],
            'trial_duration' => [
                'required_if:trial_enabled,true',
                'integer',
                'min:0',
                'max:255',
            ],
            'bonus_enabled' => ['nullable', new BooleanRule()],
            'bonus_duration' => [
                'required_if:bonus_enabled,true',
                'integer',
                'min:0',
                'max:255',
            ],
            'scopes' => [
                'required',
                'array',
                'exists:scopes,id',
                function ($attribute, $value, $fail) {
                    $duplicated = $this->checkServices($value);

                    if (count($duplicated) > 0) {
                        $fail(__("The following services (:services) contain duplicate roles", ['services' => implode(', ', $duplicated)]));
                    }
                }
            ],
            'prices' => ['required', 'array'],
            'prices.*.billing_period' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (empty(billing_get_period($value))) {
                        $fail("The billing period is invalid");
                    }
                }
            ],
            'prices.*.currency' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (empty(billing_get_currency($value))) {
                        $fail("The billing period is invalid");
                    }
                }
            ],
            'prices.*.amount' => ['required', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($request, $plan) {
            $plan = $plan->fill($request->except('description'));
            $plan->description = Purify::clean($request->description);
            $plan->slug = $this->slug($request->name);
            $plan->save();

            $plan->scopes()->attach($request->scopes);

            foreach ($request->prices as $key => $value) {
                $plan->prices()->create([
                    'amount' => $value['amount'],
                    'billing_period' => $value['billing_period'],
                    'currency' => $value['currency']
                ]);
            }
        });

        return $this->showOne($plan, $plan->transformer, 201);
    }

    /**
     * show one resource
     * @param \App\Models\Subscription\Plan $plan
     * @return void
     */
    public function show(Plan $plan)
    {
        return $this->showOne($plan, $plan->transformer);
    }

    /**
     * Update resource
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subscription\Plan $plan
     * @return void
     */
    public function update(Request $request, Plan $plan)
    {
        $this->validate($request, [
            'name' => ['nullable', 'max:150'],
            'description' => ['nullable'],
            'active' => ['nullable', new BooleanRule()],
            'trial_enabled' => ['nullable', new BooleanRule()],
            'trial_duration' => [
                'required_if:trial_enabled,true',
                'integer',
                'min:0',
                'max:255',
            ],
            'bonus_enabled' => ['nullable', new BooleanRule()],
            'bonus_duration' => [
                'required_if:bonus_enabled,true',
                'integer',
                'min:0',
                'max:255',
            ],
            'scopes' => [
                'required',
                'array',
                'exists:scopes,id',
                function ($attribute, $value, $fail) use ($plan) {

                    $duplicated = $this->checkServices($value);

                    if (count($duplicated) > 0) {
                        $fail(__("The following services (:services) contain duplicate roles", ['services' => implode(', ', $duplicated)]));
                    }
                }
            ],
            'prices' => ['nullable', 'array'],
            'prices.*.billing_period' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (empty(billing_get_period($value))) {
                        $fail("The billing period is invalid");
                    }
                }
            ],
            'prices.*.currency' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (empty(billing_get_currency($value))) {
                        $fail("The billing period is invalid");
                    }
                }
            ],
            'prices.*.amount' => ['required', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($request, $plan) {
            $update = false;

            if ($request->has('name') && $plan->name != $request->name) {
                $update = true;
                $plan->name = $request->name;
            }

            if ($request->has('description') && $plan->description != $request->description) {
                $update = true;
                $plan->description = Purify::clean($request->description);
            }

            if ($request->has('active') && $plan->active != $request->active) {
                $update = true;
                $plan->active = $request->active;
            }

            if ($request->has('trial_enabled') && $plan->trial_enabled != $request->trial_enabled) {
                $update = true;
                $plan->trial_enabled = $request->trial_enabled;
            }

            if ($request->has('trial_duration') && $plan->trial_duration != $request->trial_duration) {
                $update = true;
                $plan->trial_duration = $request->trial_duration;
            }

            if ($request->has('bonus_enabled') && $plan->bonus_enabled != $request->bonus_enabled) {
                $update = true;
                $plan->bonus_enabled = $request->bonus_enabled;
            }

            if ($request->has('bonus_duration') && $plan->bonus_duration != $request->bonus_duration) {
                $update = true;
                $plan->bonus_duration = $request->bonus_duration;
            }

            if ($update) {
                $plan->save();
            }

            if (!empty($request->scopes)) {

                $plan->scopes()->syncWithoutDetaching($request->scopes);
            }

            if (!empty($request->prices)) {
                foreach ($request->prices as $key => $value) {
                    $plan->prices()->updateOrCreate(
                        [
                            'billing_period' => strtolower($value['billing_period']),
                        ],
                        [
                            'billing_period' => strtolower($value['billing_period']),
                            'amount' => $value['amount'],
                            'currency' => $value['currency']
                        ]
                    );
                }
            }
        });

        return $this->showOne($plan, $plan->transformer, 200);
    }

    /**
     * Function to destroy plans
     * @param \App\Models\Subscription\Plan $plan
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Plan $plan)
    {
        $plan->scopes()->detach();

        $plan->prices()->delete();

        $plan->delete();

        return $this->message(__("Plan deleted successfully"), 200);
    }

    /**
     * Check duplicated scopes in the same service
     * @param mixed $value
     * @return array
     */
    public function checkServices($value)
    {
        $services = [];

        foreach ($value as $key) {
            $scope = Scope::find($key);
            array_push($services, $scope->service->slug);
        }

        $count = array_count_values($services);

        $duplicated = array_keys(array_filter($count, function ($amount) {
            return $amount > 1;
        }));

        return $duplicated;
    }
}
