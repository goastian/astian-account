<?php

namespace App\Http\Controllers\User;

use App\Models\User\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController as Controller;
use App\Transformers\Role\RoleTransformer;

class RoleController extends Controller
{

    public function __construct(){
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Role $role)
    {
        $roles = $role->all();

        return $this->showAll($roles, RoleTransformer::class);
    }
}
