<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\GlobalController as Controller;
use App\Http\Requests\UserRole\DestroyRequest;
use App\Http\Requests\UserRole\StoreRequest;
use App\Models\User\Employee;
use App\Models\User\Role;
use App\Transformers\Auth\EmployeeRoleTransformer;
use App\Transformers\Role\RoleTransformer;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class UserRoleController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('transform.request:' . EmployeeRoleTransformer::class)->only('store', 'update');
        $this->middleware('scope:account_read')->only('index');
        $this->middleware('scope:account_register')->only('store');
        $this->middleware('scope:account_update')->only('update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Employee $user)
    {
        $roles = $user->roles()->get();

        return $this->showAll($roles, EmployeeRoleTransformer::class, 200, false);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, Employee $user)
    {

        throw_if(Role::find($request->role_id)->public, new ReportError(__("this is a public role"), 403));

        DB::transaction(function () use ($request, $user) {
            $user->roles()->syncWithoutDetaching($request->role_id);
        });

        $this->privateChannel("StoreEmployeeRoleEvent", "Added new role");

        return $this->showOne(Role::find($request->role_id), RoleTransformer::class, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRequest $request, Employee $user, Role $role)
    {
        DB::transaction(function () use ($role, $user) {
            $user->roles()->detach($role->id);
        });

        $this->privateChannel("DestroyEmployeeRoleEvent", "Role remove");

        return $this->showOne($role, $role->transformer);
    }
}
