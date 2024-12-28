<?php

namespace App\Models\User;

use App\Models\Master;
use App\Transformers\Auth\GroupTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Master
{
    use HasFactory;

    public $table = "groups";

    public $timestamps = false;

    // public $view = "";

    public $transformer = GroupTransformer::class;

    protected $fillable = [
        'name',
        'description',
        'system',
        'slug'
    ];

    public $casts = [
        'system' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, "group_user", "group_id", "user_id");
    }

    /**
     * default groups
     */
    public static function groupByDefault()
    {
        return json_decode(file_get_contents(base_path('database/extra/groups.json')));
    }
}
