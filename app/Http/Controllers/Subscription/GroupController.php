<?php
namespace App\Http\Controllers\Subscription;

use Illuminate\Http\Request;
use App\Models\Subscription\Group;
use Illuminate\Support\Facades\DB;
use Elyerr\ApiResponse\Exceptions\ReportError;
use App\Http\Controllers\GlobalController as Controller;

class GroupController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:administrator_group_full,administrator_group_view')->only('index');
        $this->middleware('scope:administrator_group_full,administrator_group_show')->only('show');
        $this->middleware('scope:administrator_group_full,administrator_group_create')->only('store');
        $this->middleware('scope:administrator_group_full,administrator_group_update')->only('update');
        $this->middleware('scope:administrator_group_full,administrator_group_destroy')->only('destroy');
        //$this->middleware('scope:administrator_group_full,administrator_group_enable')->only('enable');
        //$this->middleware('scope:administrator_group_full,administrator_group_disable')->only('disable');
    }

    /**
     * Display a listing of the resource.
     * @param \App\Models\Subscription\Group $group
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Group $group)
    {
        $this->checkMethod('get');

        $params = $this->filter_transform($group->transformer);

        $data = $group->query();

        $data = $this->searchByBuilder($data, $params);

        return $this->showAllByBuilder($data, $group->transformer);
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
            'system' => ['required', 'boolean'],
        ]);

        $request->merge([
            'slug' => $this->slug($request->name),
        ]);

        $this->checkMethod('post');
        $this->checkContentType($this->getPostHeader());

        DB::transaction(function () use ($request, $group) {
            $group = $group->fill($request->all());
            $group->save();

            $this->privateChannel("GroupCreated", "New group created");
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
        $this->checkMethod('get');
        $this->checkContentType(null);

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

        $this->checkMethod('put');
        $this->checkContentType($this->getUpdateHeader());

        DB::transaction(function () use ($request, $group) {

            if ($this->is_different($group->description, $request->description)) {
                $group->description = $request->description;
                $group->push();

                $this->privateChannel("GroupUpdated", "Group updated");
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
        $this->checkMethod('delete');
        $this->checkContentType(null);

        if ($group->services()->count() === 0 && $group->users()->count()) {
            new ReportError(__("This action cannot be completed because this group is currently in use by another resource."), 403);
        }

        throw_if($group->system, new ReportError(__("This group cannot be deleted because it is a system group."), 403));

        collect(Group::groupByDefault())->map(function ($value, $key) use ($group) {
            throw_if($value->name == $group->name, new ReportError(__("This group cannot be deleted because it is a system group."), 403));
        });

        $group->forceDelete();

        $this->privateChannel("GroupDeleted", "Group deleted");

        return $this->showOne($group, $group->transformer);
    }
}
