<?php
namespace App\Models\Subscription;

use App\Models\Master as master;
use App\Models\Subscription\Scope;
use App\Transformers\Subscription\RoleTransformer;

class Role extends master
{

    /**
     * Table name
     * @var string
     */
    public $table = "roles";

    /**
     * Transformer class
     * @var 
     */
    public $transformer = RoleTransformer::class;

    /**
     * Fillable attributes
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'system',

    ];

    /**
     * Casts properties
     * @var array
     */
    public $casts = [
        "system" => "boolean",
    ];


    /**
     * default roles
     * @return mixed
     */
    public static function rolesByDefault()
    {
        return json_decode(file_get_contents(base_path('database/extra/roles.json')));
    }

    /**
     * Relationship with scopes 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scopes()
    {
        return $this->hasMany(Scope::class);
    }
}
