<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\GlobalController as Controller;
use App\Models\User\Role;
use App\Transformers\Role\RoleTransformer;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('transform.request:' . RoleTransformer::class)->only('store', 'update');
        $this->middleware('scope:scopes_read')->only('index');
        $this->middleware('scope:scopes_register')->only('store');
        $this->middleware('scope:scopes_update')->only('store');
        $this->middleware('scope:scopes_destroy')->only('destory');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Role $role)
    {
        $params = $this->filter_transform($role->transformer);

        $roles = $this->search($role->table, $params);

        return $this->showAll($roles, $role->transformer, 200, false);
    }

    public function show(Role $role)
    {
        return $this->showOne($role, $role->transformer);
    }

    public function store(Request $request, Role $role)
    {
        $this->validate($request, [
            'name' => ['required', 'unique:roles,name'],
            'description' => ['required', 'max:300'],
            'public' => ['nullable', 'boolean'],
            'required_payment' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($request, $role) {
            $role = $role->fill($request->except('name'));
            $role->name = preg_replace('/[\s\-,*;?!¡}\]\[{]/', '_', $request->name);
            $role->save();
        });

        //send event
        $this->privateChannel("StoreRoleEvent", "New role created");

        return $this->showOne($role, $role->transformer, 201);
    }

    public function update(Request $request, Role $role)
    {
        $this->validate($request, [
            'name' => ['nullable', 'unique:roles,name,' . $role->id],
            'description' => ['nullable', 'max:300'],
            'public' => ['nullable', 'boolean'],
            'required_payment' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($request, $role) {

            $can_update = false;

            if ($this->is_diferent($role->name, $request->name)) {
                $can_update = true;

                /**
                 * Check if the scope is not a default scope.
                 */
                collect(Role::rolesByDefault())->map(function ($value, $key) use ($role) {
                    throw_if($role->name == $key, new ReportError(__("This role ($key) is a default system role and cannot be deleted."), 403));
                });

                $role->name = preg_replace('/[\s\-,*;?!¡}\]\[{]/', '_', $request->name);
            }

            if ($this->is_diferent($role->description, $request->description)) {
                $can_update = true;
                $role->description = $request->description;
            }

            if ($role->public != $request->public) {
                $can_update = true;
                $role->public = $request->public;
            }

            if ($role->required_payment != $request->required_payment) {
                $can_update = true;
                $role->required_payment = $request->required_payment;
            }

            if ($can_update) {
                $role->push();
                //send event
                $this->privateChannel("UpdateRoleEvent", "Role updated");
            }

        });

        return $this->showOne($role, $role->transformer, 201);
    }

    public function destroy(Role $role)
    {
        collect(Role::rolesByDefault())->map(function ($value, $key) use ($role) {
            throw_if($value->scope == $role->name, new ReportError(__("This role ($value->scope) is a default system role and cannot be deleted."), 403));
        });

        $role->delete();

        //send event
        $this->privateChannel("DestroyRoleEvent", "Role deleted");

        $this->showOne($role, $role->transformer);
    }
}
