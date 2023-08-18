<?php

namespace App\Models\User;

use App\Models\Auth;
use App\Assets\Timestamps;
use App\Transformers\Auth\EmployeeTransformer;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Employee extends Auth
{
    use SoftDeletes, Timestamps;
    
    public $table = "employees";

    //public $view = "";

    public $transformer = EmployeeTransformer::class;

    public function roles()
    {
       return $this->belongsToMany(Role::class, 'employee_role');
    }
}   
