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
     * @return array
     */
    public function referLinks()
    {
        if (empty($this->code)) {
            return [];
        }

        return [
            'For sales' => route('plans.index', ['referral_code' => $this->code]),
            'For register' => route('register', ['referral_code' => $this->code])
        ];
    }

    /**
     * Generate code
     * @param mixed $prefix
     * @param mixed $length
     * @return string
     */
    public static function generateReferralCode($length = 32)
    {
        $prefix = strtoupper(substr(auth()->user()->name, 0, 2));
        $random = strtoupper(Str::random($length));
        return $prefix . "_" . $random;
    }

    /**
     * Update commission rate
     * @param mixed $percentage
     * @return void
     */
    public function updateCommissionRate($percentage)
    {
        $this->commission_rate = $percentage;
        $this->push();
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
