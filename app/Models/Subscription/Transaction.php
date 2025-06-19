<?php
namespace App\Models\Subscription;

use App\Models\Master;
use App\Models\User\User;
use App\Models\User\Partner;
use App\Models\Subscription\Package;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Transformers\Subscription\TransactionTransformer;

class Transaction extends Master
{
    use HasFactory;

    public $table = "transactions";

    public $transformer = TransactionTransformer::class;

    protected $fillable = [
        'currency',
        'status',
        'tax_rate_id',
        'tax_percentage',
        'tax_amount',
        'tax_inclusive',
        'tax_applied',
        'subtotal',
        'total',
        'payment_method',
        'billing_period',
        'renew',
        'session_id',
        'payment_intent_id',
        'payment_url',
        'response', //save response
        'meta', //save package
        'code',
        'package_id',
        'partner_id',
        'partner_commission_rate',
        'payment_method_id'
    ];

    protected $casts = [
        'response' => 'array',
        'renew' => 'boolean',
        'tax_inclusive' => 'boolean',
        'tax_applied' => 'boolean',
        'meta' => 'array'
    ];

    /**
     * User
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Plan
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Partner
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    } 
}
