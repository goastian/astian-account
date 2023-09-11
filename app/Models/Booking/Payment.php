<?php

namespace App\Models\Booking;
 
use App\Assets\Timestamps;
use App\Models\master;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\Payment\PaymentTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends master
{
    use HasFactory, Timestamps;

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
