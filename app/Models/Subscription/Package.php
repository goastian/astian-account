<?php
namespace App\Models\Subscription;

use App\Models\Master;
use App\Models\User\User;
use App\Models\Subscription\Transaction;
use App\Transformers\Subscription\PackageTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Master
{
    use HasFactory;

    /**
     * table name
     * @var string
     */
    public $table = "packages";

    /**
     * Transformer 
     * @var 
     */
    public $transformer = PackageTransformer::class;

    /**
     * Fillable
     * @var array
     */
    protected $fillable = [
        'status',
        'start_at',
        'end_at',
        'cancellation_at',
        'last_renewal_at',
        'is_recurring',
        'transaction_code',
        'meta',
        'user_id',
    ];

    protected $casts = [
        'meta' => 'array',
        'is_recurring' => 'boolean',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'cancellation_at' => 'datetime',
        'last_renewal_at' => 'datetime',
    ];

    /**
     * Get the user that owns the UserSubscription
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The last transaction
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lastTransaction()
    {
        return $this->hasOne(Transaction::class, 'code', 'transaction_code');
    }

    /**
     * transactions
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * check if it the transaction is cancelled
     * @return bool
     */
    public function isCancelled()
    {
        return $this->cancellation_at ? true : false;
    }
}
