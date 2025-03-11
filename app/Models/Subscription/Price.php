<?php
namespace App\Models\Subscription;

use App\Models\Master;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Price extends Master
{
    use HasFactory;

    public $fillable = [
        'amount',
        'billing_period'
    ];

    /**
     * Billing period
     * @return array{annual: \Carbon\Carbon|\Carbon\CarbonInterface, biweekly: \Carbon\Carbon|\Carbon\CarbonInterface, daily: \Carbon\Carbon|\Carbon\CarbonInterface, monthly: \Carbon\Carbon|\Carbon\CarbonInterface, quarterly: \Carbon\Carbon|\Carbon\CarbonInterface, semiannual: \Carbon\Carbon|\Carbon\CarbonInterface, weekly: \Carbon\Carbon|\Carbon\CarbonInterface}
     */
    public function billingPeriod()
    {
        $now = Carbon::now();

        return [
            "daily" => $now->addDay(),
            "weekly" => $now->addWeek(),
            "biweekly" => $now->addWeeks(2),
            "monthly" => $now->addMonth(),
            "quarterly" => $now->addMonths(3),
            "semiannual" => $now->addMonths(6),
            "annual" => $now->addYear(),
        ];
    }

    /**
     *  priceable
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function priceable()
    {
        return $this->morphTo();
    }
}
