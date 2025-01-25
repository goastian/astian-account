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
        /*$this->middleware('scope:groups_fullgroup_read')->only('index', 'show');
        $this->middleware('scope:gruop_create')->only('store');
        $this->middleware('scope:group_update')->only('update');
        $this->middleware('scope:group_destroy')->only('destroy');*/
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

        foreach ($params as $key => $value) {
            $data = $data->where($key, "like", "%" . $value . "%");
        }

        $data = $data->get();


        return $this->showAll($data, $group->transformer);
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

        collect(Group::groupByDefault())->map(function ($value, $key) use ($group) {
            throw_if($value->name == $group->name, new ReportError(__("This action can't be done"), 400));
        });

        throw_if($group->services()->count() > 0, new ReportError(__("This action can't be done"), 400));

        $group->delete();

        $this->privateChannel("GroupDeleted", "Group deleted");

        return $this->showOne($group, $group->transformer);
    }
}
