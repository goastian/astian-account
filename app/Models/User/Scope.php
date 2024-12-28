<?php

namespace App\Models\User;

use App\Models\Master;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Scope extends Master
{
    use HasFactory;

    /**
     * Name of table
     * @var string
     */
    protected $table = "scopes";

    protected $transformer = "";

    /**
     * Type of model
     * @var string
     */
    private $type = "scope";

    protected $fillable = [
        'service_id',
        'role_id',
        'requires_payment',
        'public',
        'active',
        'price'
    ];

    /**
     * Append new properties
     * @var array
     */
    protected $append = [
        'type'
    ];

    /**
     * return the type for this model
     * @return mixed
     */
    public function getTypeAttribute()
    {
        return $this->type;
    }
}
