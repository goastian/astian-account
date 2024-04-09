<?php

namespace App\Http\Controllers\Role;

use App\Events\Role\DestroyRoleEvent;
use App\Events\Role\StoreRoleEvent;
use App\Events\Role\UpdateRoleEvent;
use App\Http\Controllers\GlobalController as Controller;
use App\Models\User\Role;
use App\Transformers\Role\RoleTransformer;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

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

        return $this->showAll($roles, $role->transformer);
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

        StoreRoleEvent::dispatch($this->authenticated_user());

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
                 * verifica que el nombre del escope no sea uno por defecto
                 */
                collect(Role::rolesByDefault())->map(function ($value, $key) use ($role) {
                    if ($role->name == $key) {
                        throw new ReportError(Lang::get("This role ($key) belongs to the system by default and the scope name cannot be updated."), 403);
                    }
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
                UpdateRoleEvent::dispatch($this->authenticated_user());
            }

        });

        return $this->showOne($role, $role->transformer, 201);
    }

    public function destroy(Role $role)
    {
        collect(Role::rolesByDefault())->map(function ($value, $key) use ($role) {
            if ($key === $role->name) {
                throw new ReportError(Lang::get("This role ($key) belongs to the system by default and cannot be deleted"), 403);
            }
        });

        $role->delete();

        DestroyRoleEvent::dispatch($this->authenticated_user());

        $this->showOne($role, $role->transformer);
    }

}
