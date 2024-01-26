<?php

namespace App\Http\Controllers\Role;

use App\Models\User\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController as Controller;
use App\Models\User\Employee;

class RoleUserController extends Controller
{

    public function __construct(){
        parent::__construct();
        $this->middleware('scope:account_read,scope_read');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Role $role, Employee $employee)
    {
        $users = $role->users()->get();

        return $this->showAll($users, $employee->transformer);
    }
}
