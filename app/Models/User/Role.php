<?php

namespace App\Models\User;

use App\Transformers\Role\RoleTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public $table = "roles";

    public $view = "roles";

    public $transformer = RoleTransformer::class;

    protected $fillable = [
        //
    ];
    
    public function users()
    {
      return  $this->belongsToMany(Employee::class, 'employee_role');
    }
}
