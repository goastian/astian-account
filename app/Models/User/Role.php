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
    ];

    public function users()
    {
        return $this->belongsToMany(Employee::class, 'role_user', 'role_id', 'user_id');
    }

    public static function rolesByDefault()
    {
        return [
            "admin" => "full system access",
            "client" => "default access for the client",
            "broadcast" => "full access to manage broadcasting channels",
            "account_read" => "granted to read user data",
            "account_register" => "granted to register new users",
            "account_update" => "granted to update users data",
            "account_enable" => "granted to eanble just the only users, this does not apply for clients",
            "account_disable" => "granted to disable users accounts, this does not apply for clients",
            "scopes_read" => "granted to read scopes",
            "scopes_register" => "granted to register new roles",
            "scopes_update" => "granted to update scopes, just only aplly for description",
            "scopes_destroy" => "granted to delete roles",
            "notify" => "granted to send notification push",
        ];
    }
}
