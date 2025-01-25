<?php
namespace App\Http\Controllers\Subscription;

use Illuminate\Http\Request;
use App\Models\Subscription\Role;
use Illuminate\Support\Facades\DB;
use Elyerr\ApiResponse\Exceptions\ReportError;
use App\Http\Controllers\GlobalController as Controller;

class RoleController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        /*$this->middleware('scope:scope_read')->only('index', 'show');
        $this->middleware('scope:scope_create')->only('store');
        $this->middleware('scope:scope_update')->only('update');
        $this->middleware('scope:scope_destroy')->only('destroy');*/
    }

    /**
     *  Display a listing of the resource.
     * @param \App\Models\Subscription\Role $role
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Role $role)
    {
        $this->checkMethod('get');

        $params = $this->filter_transform($role->transformer);

        $data = $role->query();

        foreach ($params as $key => $value) {
            $data = $data->where($key, "like", "%" . $value . "%");
        }

        $data = $data->get();

        return $this->showAll($data, $role->transformer);
    }

    /**
     * Show one resource
     * @param \App\Models\Subscription\Role $role
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Role $role)
    {
        $this->checkMethod('get');
        $this->checkContentType(null);

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

        $this->checkMethod('post');
        $this->checkContentType($this->getPostHeader());

        $request->merge([
            'slug' => $this->slug($request->name),
        ]);

        DB::transaction(function () use ($request, $role) {
            $role = $role->fill($request->all()); 
            $role->save();
        });

        //send event
        $this->privateChannel("StoreRoleEvent", "New role created");

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
            'system' => ['nullable', 'boolean']
        ]);

        $this->checkMethod('put');
        $this->checkContentType($this->getUpdateHeader());

        DB::transaction(function () use ($request, $role) {

            $can_update = false;

            if ($this->is_different($role->description, $request->description)) {
                $can_update = true;
                $role->description = $request->description;
            }

            if ($this->is_different($role->system, $request->system)) {
                $role->system = $request->system;
                $can_update = true;
            }

            if ($can_update) {              

                $role->push();
                //send event
                $this->privateChannel("UpdateRoleEvent", "Role updated");
            }

        });

        return $this->showOne($role, $role->transformer, 201);
    }

    /**
     * Destroy resources
     * @param \App\Models\Subscription\Role $role
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Role $role)
    {
        $this->checkMethod('delete');
        $this->checkContentType(null);

        collect(Role::rolesByDefault())->map(function ($value, $key) use ($role) {
            throw_if($value->name == $role->name, new ReportError(__("This action can't be done"), 400));
        });

        throw_if($role->scopes()->count() > 0, new ReportError(__("This action can't be done"), 400));

        $role->delete();

        //send event
        $this->privateChannel("DestroyRoleEvent", "Role deleted");

        return $this->showOne($role, $role->transformer);
    }
}
