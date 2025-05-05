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
        'billing_period',
        'currency'
    ];

    /**
     *  priceable
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function priceable()
    {
        return $this->morphTo();
    }    
}
