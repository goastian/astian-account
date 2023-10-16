<?php

namespace App\Models\User;

use App\Models\Master as master;
use App\Transformers\Role\RoleTransformer; 

class Role extends master
{

    public $table = "roles";

    public $view = "roles";

    public $transformer = RoleTransformer::class;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description'
    ];
    
    public function users()
    {
      return  $this->belongsToMany(Employee::class, 'employee_role');
    }
}
