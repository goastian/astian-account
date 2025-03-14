<?php

namespace App\Models\Subscription;

use App\Models\Master;
use App\Models\Subscription\Group;
use App\Models\Subscription\Scope;
use App\Transformers\Subscription\ServiceTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Master
{
    use HasFactory;

    /**
     * Name of table
     * @var string
     */
    public $table = "services";

    /**
     * Transformer of class
     * @var 
     */
    public $transformer = ServiceTransformer::class;

    /**
     * Fillable attributes
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'group_id',
        'system'
    ];

    protected $casts = [
        'system' => 'boolean',
    ];

    /**
     * Relationship with groups
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
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
