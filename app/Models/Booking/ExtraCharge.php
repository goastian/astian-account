<?php

namespace App\Models\Booking;

use App\Models\Master as master;  
use App\Transformers\Booking\ExtraChargeTransformer; 

class ExtraCharge extends master
{
    public $table = "extra_charges";

    public $view = "";

    public $transformer = ExtraChargeTransformer::class;

    protected $fillable = [
        'charge',
        'amount',
        'price',
    ];

    protected $appends = [
        'total',
    ];

    public function extra_chargeable()
    {
        return $this->morphTo();
    }

    public function setChargeAttribute($value)
    {
        $this->attributes['charge'] = strtolower($value);
    }

    public function getTotalAttribute()
    {
        return $this->amount * $this->price;
    }

}
