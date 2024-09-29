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
        'description',
        'public',
        'required_payment',
    ];

    public function users()
    {
        return $this->belongsToMany(Employee::class, 'role_user', 'role_id', 'user_id');
    }

    /**
     * Get all roles or scopes
     * 
     * @return Array
     */
    public static function rolesByDefault()
    {
        return json_decode(file_get_contents(base_path('database/extra/roles.json')));
    }
}
