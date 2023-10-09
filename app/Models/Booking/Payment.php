<?php

namespace App\Models\Booking;
 
use App\Models\Master as master; 
use App\Transformers\Payment\PaymentTransformer; 

class Payment extends master
{
    public $table = "payments";

    public $view = "payment";

    public $transformer = PaymentTransformer::class;

    protected $fillable = [
        'price',
        'description',
        'type',
        'code',
        'method',
    ];
 
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = strtolower($value);
    }

    public function paymentable()
    {
        return $this->morphTo();
    }

}
