<?php
namespace App\Http\Controllers\Web\Admin\Subscription;

use App\Http\Controllers\WebController;
use App\Rules\BooleanRule;
use Illuminate\Http\Request;
use App\Models\Subscription\Group;
use Illuminate\Support\Facades\DB;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Inertia\Inertia;

class GroupController extends WebController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator_group_full,administrator_group_view')->only('index');
        $this->middleware('userCanAny:administrator_group_full,administrator_group_show')->only('show');
        $this->middleware('userCanAny:administrator_group_full,administrator_group_create')->only('store');
        $this->middleware('userCanAny:administrator_group_full,administrator_group_update')->only('update');
        $this->middleware('userCanAny:administrator_group_full,administrator_group_destroy')->only('destroy');
        $this->middleware('userCanAny:administrator_group_full,administrator_group_enable')->only('enable');
        $this->middleware('userCanAny:administrator_group_full,administrator_group_disable')->only('disable');

        $this->middleware('wants.json')->only('show');
    }

    /**
     * index
     * @param \App\Models\Subscription\Group $group
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Group $group)
    {
        // Retrieve params of the request
        $params = $this->filter_transform($group->transformer);

        // Prepare query
        $data = $group->query();

        // Search
        $data = $this->searchByBuilder($data, $params);

        if (request()->wantsJson()) {
            return $this->showAllByBuilder($data, $group->transformer);
        }

        return Inertia::render(
            "Admin/Groups/Index",
            [
                "route" => route('admin.groups.index')
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subscription\Group $group
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Group $group)
    {
        $this->validate($request, [
            'name' => [
                'required',
                'max:100',
                function ($attribute, $value, $fail) use ($request, $group) {
                    $slug = $this->slug($value);

                    $checkSlug = $group->where('slug', $slug)->first();
                    if ($checkSlug) {
                        $fail(__("The :attribute already exists", ['attribute' => $attribute]));
                    }
                }
            ],
            'description' => ['required', 'max:190'],
            'system' => ['required', new BooleanRule()],
        ]);

        $request->merge([
            'slug' => $this->slug($request->name),
        ]);

        DB::transaction(function () use ($request, $group) {
            $group = $group->fill($request->all());
            $group->save();

        });

        return $this->showOne($group, $group->transformer, 201);
    }

    /**
     * Display the specified resource.
     * @param \App\Models\Subscription\Group $group
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Group $group)
    {
        return $this->showOne($group, $group->transformer);
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subscription\Group $group
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Group $group)
    {
        $this->validate($request, [
            'description' => ['nullable', 'max:200'],
        ]);


        DB::transaction(function () use ($request, $group) {

            if ($request->has('description') && $group->description != $request->description) {
                $group->description = $request->description;
                $group->push();


            }
        });

        return $this->showOne($group, $group->transformer, 201);
    }

    /**
     * destroy resources
     * @param \App\Models\Subscription\Group $group
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Group $group)
    {
        if ($group->services()->count() === 0 && $group->users()->count()) {
            new ReportError(__("This action cannot be completed because this group is currently in use by another resource."), 403);
        }

        throw_if($group->system, new ReportError(__("This group cannot be deleted because it is a system group."), 403));

        collect(Group::groupByDefault())->map(function ($value, $key) use ($group) {
            throw_if($value->name == $group->name, new ReportError(__("This group cannot be deleted because it is a system group."), 403));
        });

        $group->forceDelete();



        return $this->showOne($group, $group->transformer);
    }
}
