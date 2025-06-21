<?php
namespace App\Models\Subscription;

use App\Models\Master;
use App\Models\Subscription\Price;
use App\Models\Subscription\Scope;
use App\Transformers\Subscription\PlanTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Transformers\Subscription\PlanPriceTransformer;
use App\Transformers\Subscription\PlanScopeTransformer;

class Plan extends Master
{
    use HasFactory;

    protected $table = "plans";

    public $transformer = PlanTransformer::class;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'active',
        'bonus_activated',
        'bonus_duration',
        'trial_enabled',
        'trial_duration'
    ];

    protected $casts = [
        'public' => 'boolean',
        'active' => 'boolean',
        'bonus_activate' => 'boolean'
    ];

    /**
     * Show the scopes available for the plan
     */
    public function assignedScopes($scopes)
    {
        $scopes = $scopes->where('active', 1);
        return $this->transform($scopes, new PlanScopeTransformer($this));
    }

    /**
     * Relationship with scopes
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function scopes()
    {
        return $this->belongsToMany(Scope::class);
    }

    /**
     * Summary of prices
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function prices()
    {
        return $this->morphMany(Price::class, 'priceable');
    }
}
