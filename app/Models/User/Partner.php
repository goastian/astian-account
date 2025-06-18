<?php

namespace App\Models\User;

use App\Models\Master;
use App\Models\User\User;
use Illuminate\Support\Str;
use App\Models\Subscription\Transaction;
use App\Transformers\Partner\PartnerTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Partner extends Master
{
    use HasFactory;

    /**
     * Transformer
     * @var 
     */
    public $transformer = PartnerTransformer::class;

    public $fillable = [
        'code',
        'commission_rate',
        'user_id'
    ];

    /**
     * Belongs to user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * transactions
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Referral Link
     * @return string|null
     */
    public function referLinks()
    {
        return $this->code ? route('plans.index', ['referral_code' => $this->code]) : null;
    }

    /**
     * Retrieve the commission rate
     * @return float|int
     */
    public function getCommissionRate()
    {
        return $this->commission_rate / 100;
    }
}
