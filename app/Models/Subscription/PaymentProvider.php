<?php

namespace App\Models\Subscription;

use App\Models\Master;
use App\Models\User\User;

class PaymentProvider extends Master
{

    protected $table = 'payment_providers';

    protected $fillable = [
        'user_id',
        'name',
        'customer_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
