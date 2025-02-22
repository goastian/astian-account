<?php
namespace App\Models\Subscription;

use App\Models\Master;
use App\Models\Subscription\Scope;
use App\Models\Subscription\Package;
use App\Transformers\Subscription\PlanTransformer;
use App\Transformers\Subscription\ScopeTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Master
{
    use HasFactory;

    protected $table = "plans";

    public $transformer = PlanTransformer::class;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'public',
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

}
