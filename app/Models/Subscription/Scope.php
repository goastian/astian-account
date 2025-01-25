<?php
namespace App\Models\Subscription;

use App\Models\Master; 
use App\Models\Subscription\Role;
use App\Models\User\UserScope;
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
        'requires_payment',
        'public',
        'active',
        'price'
    ];

    /**
     * Casts properties
     * @var array
     */
    protected $casts = [
        'requires_payment' => 'boolean',
        'public' => 'boolean',
        'active' => 'boolean',
    ];


    /**
     * Relationship with service
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
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
    public function getScopeID()
    {
        $group = $this->service->group->slug;
        $service = $this->service->slug;
        $role = $this->role->slug;
        return "{$group}_{$service}_{$role}";
    }
}
