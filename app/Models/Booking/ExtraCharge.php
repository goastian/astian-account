<?php

namespace App\Models\Booking;

use App\Assets\Timestamps;
use App\Models\master;
use App\Transformers\Booking\ExtraChargeTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraCharge extends master
{
    use HasFactory, Timestamps;

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
