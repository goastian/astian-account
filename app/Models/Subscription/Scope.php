<?php
namespace App\Models\Subscription;

use App\Models\Master;
use App\Models\User\UserScope;
use App\Models\Subscription\Plan;
use App\Models\Subscription\Role;
use App\Transformers\Subscription\ScopeTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Scope extends Master
{
    use HasFactory;

    /**
     * Name of table
     * @var string
     */
    protected $table = "scopes";

    public $transformer = ScopeTransformer::class;

    protected $fillable = [
        'service_id',
        'role_id',
        'public',
        'active',
        'api_key'
    ];

    protected $appends = [
        'gsr_id'
    ];

    /**
     * Casts properties
     * @var array
     */
    protected $casts = [
        'public' => 'boolean',
        'active' => 'boolean',
        'api_key' => 'boolean'
    ];

    /**
     * get scope name
     * @return string
     */
    public function getGsrIdAttribute()
    {
        return $this->getGsrID();
    }

    /**
     * Relationship with service
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Summary of plan
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function plan()
    {
        return $this->belongsToMany(Plan::class);
    }

    /**
     * Relationship with scope
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relationship with the scopes
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scopeUsers()
    {
        return $this->hasMany(UserScope::class);
    }

    /**
     * Generate the scope id
     * @return string
     */
    public function getGsrID()
    {
        $group = $this->service->group->slug;
        $service = $this->service->slug;
        $role = $this->role->slug;
        return "{$group}:{$service}:{$role}";
    }
}
