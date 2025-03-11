<?php
namespace App\Models\Subscription;

use App\Models\Master;
use App\Models\Subscription\Price;
use App\Models\Subscription\Scope;
use App\Models\Subscription\Package;
use App\Transformers\Subscription\PlanTransformer;
use App\Transformers\Subscription\ScopeTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Transformers\Subscription\PlanPriceTransformer;

class Plan extends Master
{
    use HasFactory;

    protected $table = "plans";

    public $transformer = PlanTransformer::class;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'public',
        'active'
    ];

    /**
     * Show the scopes available for the plan
     */
    public function assignedScopes()
    {
        $data = $this->scopes()->where('active', 1)->get();
        $data = fractal($data, ScopeTransformer::class);
        return !empty($data) ? json_decode(json_encode($data))->data : [];
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
     * Relationship with packages
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    /**
     * Summary of prices
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function prices()
    {
        return $this->morphMany(Price::class, 'priceable');
    }

    /**
     * Prices
     */
    public function priceable()
    {
        $prices = fractal($this->prices()->get(), PlanPriceTransformer::class);
        return json_decode(json_encode($prices))->data;
    }
}
