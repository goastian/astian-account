<?php

namespace App\Models\User;

use App\Models\master;
use App\Transformers\Role\RoleTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends master
{
    use HasFactory;

    public $table = "roles";

    public $view = "roles";

    public $transformer = RoleTransformer::class;

    public $timestamps = false;

    protected $fillable = [
        'name'
    ];
    
    public function users()
    {
      return  $this->belongsToMany(Employee::class, 'employee_role');
    }
}
