<?php

namespace App\Models\User;

use App\Models\Auth;
use App\Transformers\Auth\EmployeeTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Auth
{
    use SoftDeletes;
    
    public $table = "employees";

    //public $view = "";

    public function roles()
    {
       return $this->belongsToMany(Role::class, 'employee_role');
    }
}   
