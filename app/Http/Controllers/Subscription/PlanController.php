<?php
namespace App\Http\Controllers\Subscription;

use Illuminate\Http\Request;
use App\Models\Subscription\Plan;
use App\Models\Subscription\Scope;
use Illuminate\Support\Facades\DB;
use App\Models\Subscription\Package;
use App\Http\Controllers\GlobalController;
use Elyerr\ApiResponse\Exceptions\ReportError;

class PlanController extends GlobalController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:administrator_plan_full,administrator_plan_view')->only('index');
        $this->middleware('scope:administrator_plan_full,administrator_plan_show')->only('show');
        $this->middleware('scope:administrator_plan_full,administrator_plan_create')->only('store');
        $this->middleware('scope:administrator_plan_full,administrator_plan_update')->only('update');
        $this->middleware('scope:administrator_plan_full,administrator_plan_destroy')->only('destroy');
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

        return $this->showAllByBuilder($data, $plan->transformer);
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
            'public' => ['required', 'boolean'],
            'active' => ['required', 'boolean'],
            'scopes' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        if (!is_array($value)) {
                            $fail(__("The :attribute must be an array", ["attribute" => $attribute]));
                        }

                        foreach ($value as $key) {
                            if (!Scope::find($key)) {
                                $fail(__("The :attribute is invalid", ["attribute" => $attribute]));
                            }
                        }
                    }


                }
            ]
        ]);

        $this->checkMethod('post');

        $this->checkContentType($this->getPostHeader());

        DB::transaction(function () use ($request, $plan) {
            $plan = $plan->fill($request->all());
            $plan->slug = $this->slug($request->name);
            $plan->save();

            $plan->scopes()->attach($request->scopes);
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
        $this->checkMethod('get');

        $this->checkContentType(null);

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
            'public' => ['nullable', 'boolean'],
            'active' => ['nullable', 'boolean'],
        ]);

        $this->checkMethod('put');

        $this->checkContentType($this->getUpdateHeader());

        DB::transaction(function () use ($request, $plan) {
            $update = false;

            if ($this->is_different($plan->name, $request->name)) {
                $update = true;
                $plan->name = $request->name;
            }

            if ($this->is_different($plan->description, $request->description)) {
                $update = true;
                $plan->description = $request->description;
            }

            if ($this->is_different($plan->public, $request->public)) {
                $update = true;
                $plan->public = $request->public;
            }

            if ($this->is_different($plan->active, $request->active)) {
                $update = true;
                $plan->active = $request->active;
            }

            if ($update) {
                $plan->save();
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
        $this->checkMethod('delete');

        $this->checkContentType(null);

        throw_if(
            $plan->packages()->count() > 0,
            new ReportError(__("This action cannot be completed because this plan is currently assigned to one or more packages and cannot be deleted."), 403)
        );

        /*throw_if(
            $plan->scopes()->count() > 0,
            new ReportError(__("This action cannot be completed because this plan is currently assigned to one or more scopes and cannot be deleted."), 403)
        );*/

        $plan->scopes()->detach();

        $plan->delete();

        return $this->showOne($plan, $plan->transformer);
    }

}
