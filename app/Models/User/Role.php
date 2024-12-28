<?php

namespace App\Models\User;

use App\Models\User\User;
use App\Models\Master as master;
use App\Transformers\Role\RoleTransformer;

class Role extends master
{

    public $table = "roles";


    public $transformer = RoleTransformer::class;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'public',
        'required_payment',
        'group_id',
        'system',
    ];

    public $casts = [
        "system" => "boolean",
        "public" => "boolean",
        "required_payment" => "boolean"
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    }

    /**
     * Summary of group
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * default roles
     * @return mixed
     */
    public static function rolesByDefault()
    {
        return json_decode(file_get_contents(base_path('database/extra/roles.json')));
    }
}
