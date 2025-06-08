<?php
namespace App\Http\Controllers\Web\Admin\Subscription;

use App\Rules\StringOnlyRule;
use Inertia\Inertia;
use App\Rules\BooleanRule;
use Illuminate\Http\Request;
use App\Models\Subscription\Role;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\WebController;
use Elyerr\ApiResponse\Exceptions\ReportError;

class RoleController extends WebController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator_role_full,administrator_role_view')->only('index');
        $this->middleware('userCanAny:administrator_role_full,administrator_role_show')->only('show');
        $this->middleware('userCanAny:administrator_role_full,administrator_role_create')->only('store');
        $this->middleware('userCanAny:administrator_role_full,administrator_role_update')->only('update');
        $this->middleware('userCanAny:administrator_role_full,administrator_role_destroy')->only('destroy');

        $this->middleware('wants.json')->only('show');
    }

    /**
     *  Display a listing of the resource.
     * @param \App\Models\Subscription\Role $role
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Role $role)
    {
        // Retrieve params of the request
        $params = $this->filter_transform($role->transformer);

        // Prepare query
        $data = $role->query();

        // Search
        $data = $this->searchByBuilder($data, $params);

        // Order by
        $data = $this->orderByBuilder($data);

        if (request()->wantsJson()) {
            return $this->showAllByBuilder($data, $role->transformer);
        }

        return Inertia::render("Admin/Role/Index", [
            'route' => route('admin.roles.index')
        ]);
    }

    /**
     * Show one resource
     * @param \App\Models\Subscription\Role $role
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Role $role)
    {
        return $this->showOne($role, $role->transformer);
    }

    /**
     * Create new resource
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subscription\Role $role
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Role $role)
    {
        $this->validate($request, [
            'name' => [
                'required',
                new StringOnlyRule(),
                function ($attribute, $value, $fail) use ($role) {
                    $slug = $this->slug($value);
                    $model = $role->where('slug', $slug)->first();

                    if ($model) {
                        $fail(__("The :attribute already exists", ['attribute' => $attribute]));
                    }
                }
            ],
            'description' => ['required', 'max:190'],
        ]);

        $request->merge([
            'slug' => $this->slug($request->name),
        ]);

        DB::transaction(function () use ($request, $role) {
            $role = $role->fill($request->all());
            $role->save();
        });

        //send event


        return $this->showOne($role, $role->transformer, 201);
    }

    /**
     * Update resources
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subscription\Role $role
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Role $role)
    {
        $this->validate($request, [
            'description' => ['nullable', 'max:300'],
            'system' => ['nullable', new BooleanRule()]
        ]);

        DB::transaction(function () use ($request, $role) {

            $update = false;

            if ($request->has('description') && $role->description != $request->description) {
                $update = true;
                $role->description = $request->description;
            }

            if ($update) {
                $role->push();
                //send event

            }

        });

        return $this->showOne($role, $role->transformer, 200);
    }

    /**
     * Destroy resources
     * @param \App\Models\Subscription\Role $role
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Role $role)
    {
        collect(Role::rolesByDefault())->map(function ($value, $key) use ($role) {
            throw_if($value->name == $role->name, new ReportError(__("This action cannot be completed because this role is a system role and cannot be deleted."), 403));
        });

        throw_if($role->system, new ReportError(__("This action cannot be completed because this role is a system role and cannot be deleted."), 403));

        throw_if($role->scopes()->count() > 0, new ReportError(__("This action cannot be completed because this role is currently assigned to one or more scopes and cannot be deleted."), 403));

        $role->delete();

        //send event


        return $this->showOne($role, $role->transformer);
    }
}
