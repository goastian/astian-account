<?php

namespace App\Models\User;

use App\Models\Master;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSubscription extends Master
{
    use HasFactory;

    public $table = "user_subscriptions";

    public $view = "";

    public $transformer = "";

    protected $fillable = [
        "user_id",
        'target_type',
        'target_id',
        'price_plan',
        'price_scope',
        'start_date',
        'end_date',
        'trial_start_at',
        'trial_duration_days',
        'cancellation_date',
        'last_renewal_at',
        'next_payment_due',
        'is_recurring',
        'status',
        'system',
    ];

    /**
     * default status 
     * @return array
     */
    public function getStatus()
    {
        return ['active', 'cancelled', 'expired'];
    }

}
