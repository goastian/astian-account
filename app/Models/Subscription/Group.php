<?php
namespace App\Models\Subscription;

use App\Models\Master;
use App\Models\User\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Transformers\Subscription\GroupTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Master
{
    use HasFactory, SoftDeletes;

    public $table = "groups";

    public $timestamps = false;

    public $transformer = GroupTransformer::class;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'system',
    ];

    public $casts = [
        'system' => 'boolean',
    ];

    /**
     * Retrieve default groups for the system
     * @return mixed
     */
    public static function groupByDefault()
    {
        return json_decode(file_get_contents(base_path('database/extra/groups.json')));
    }

    /**
     * Set relationship with services
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Users
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<User, Group>
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
