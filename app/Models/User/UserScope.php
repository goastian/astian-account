<?php

namespace App\Models\User;

use App\Models\Master;
use App\Models\Subscription\Scope;
use App\Transformers\User\UserScopeTransformer;

class UserScope extends Master
{

    /**
     * Table name
     * @var string
     */
    protected $table = 'user_scope';

    /**
     * Transformer of class
     * @var 
     */
    public $transformer = UserScopeTransformer::class;

    /**
     * Fillable attributes
     * @var array
     */
    public $fillable = [
        'scope_id',
        'user_id',
        'package_id',
        'end_date',
    ];

    protected $appends = [
        'gsr_id',
    ];

    protected $casts = [
        'end_date' => 'datetime',
    ];


    public function getGsrIdAttribute()
    {
        return $this->scope->getGsrID();
    }

    /**
     * Belongs to 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Belongs to
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scope()
    {
        return $this->belongsTo(Scope::class);
    }

    public function revoked()
    {
        if (empty($this->end_date)) {
            return 'unlimited';
        }

        return $this->end_date < now() ? 'revoked' : 'active';
    }
}
