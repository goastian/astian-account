<?php

namespace App\Http\Controllers\User;

use App\Models\User\Role; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\Role\StoreRoleEvent;
use App\Events\Role\UpdateRoleEvent;
use App\Http\Controllers\GlobalController as Controller;

class RoleController extends Controller
{

    public function __construct()
    {
        parent::__construct();
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

    public function store(Request $request, Role $role)
    {

        $this->validate($request, [
            'name' => ['required', 'unique:roles,name'],
            'description' => ['required', 'max:300'],
        ]);

        DB::transaction(function () use ($request, $role) {
            $role = $role->fill($request->all());
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
        ]);

        DB::transaction(function () use ($request, $role) {

            if ($this->is_diferent($role->name, $request->name)) {
                $this->can_update[] = true;
                $role->name = $request->name;
            }

            if ($this->is_diferent($role->description, $request->description)) {
                $this->can_update[] = true;
                $role->description = $request->description;
            }

        });

        if (in_array(true, $this->can_update)) {
            UpdateRoleEvent::dispatch($this->authenticated_user());
        }
        return $this->showOne($role, $role->transformer, 201);
    }

}
