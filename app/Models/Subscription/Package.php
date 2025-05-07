<?php
namespace App\Models\Subscription;

use App\Models\Master;
use App\Models\User\User;
use App\Notifications\Subscription\PaymentFailed;
use App\Notifications\Subscription\PaymentSuccessfully;
use App\Notifications\Subscription\RenewSuccessfully;
use Illuminate\Support\Facades\Log;
use App\Models\Subscription\Transaction;
use App\Transformers\Subscription\PackageTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Transformers\Subscription\TransactionTransformer;

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
     * appends
     * @var array
     */
    protected $appends = [
        'scope'
    ];

    /**
     * Get the user that owns the UserSubscription
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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

    /**
     * Get the all transactions 
     * @param mixed $transformer
     */
    public function getTransactions($transformer = TransactionTransformer::class)
    {
        $transactions = $this->transactions()->get();

        return fractal($transactions, $transformer)->toArray()['data'];
    }

    /**
     * Calc the expiration date
     * @return mixed
     */
    public function getEndDate()
    {
        //Retrieve metadata of the plan
        $meta = $this->meta;

        //set end date
        $end_date = $this->end_at ?? now();

        if (empty($this->end_at) && $meta['bonus_enabled']) {
            $end_date->addDays($meta['bonus_duration']);
        }

        if (!empty($this->end_at) && config("billing.renew.bonus_enabled") && $meta['bonus_enabled']) {
            $end_date->addDays($meta['bonus_duration']);
        }

        //add billing period
        $period = config('billing.period.' . $meta['price']['billing_period']);
        $unit = $period['unit'];
        $interval = $period['interval'];

        return $end_date->{"add" . ucfirst($unit)}($interval);
    }

    /**
     * Add payments scopes to the user
     * @return void
     */
    public function addOrUpdatedScopeSubscription()
    {
        $user = User::find($this->user_id);

        $scopes = $this->meta['scopes'];

        foreach ($scopes as $key => $value) {
            $user->userScopes()->updateOrCreate(
                [
                    'scope_id' => $value['id'],
                    'user_id' => $user->id,
                    'package_id' => $this->id,
                ],
                [
                    'scope_id' => $value['id'],
                    'user_id' => $user->id,
                    'package_id' => $this->id,
                    'end_date' => $this->end_at,
                ]
            );
        }
    }

    /**
     * Payment successfully
     * @return void
     */
    public function paymentSuccessfully()
    {
        $user = User::find($this->user_id);

        $this->start_at = now();
        $this->end_at = $this->getEndDate();
        $this->status = config('billing.status.successful.name');
        $this->push();

        //add payments scopes
        $this->addOrUpdatedScopeSubscription();

        $user->notify(new PaymentSuccessfully());

    }

    /**
     * Renew package
     * @return void
     */
    public function renewSuccessfully()
    {
        $user = User::find($this->user_id);

        $this->end_at = $this->getEndDate();
        $this->status = config('billing.status.successful.name');
        $this->last_renewal_at = now();
        $this->push();

        //add payments scopes
        $this->addOrUpdatedScopeSubscription();

        $user->notify(new RenewSuccessfully());
    }

    /**
     * Payment successfully
     * @return void
     */
    public function paymentFailed()
    {
        $user = User::find($this->user_id);

        $this->status = config('billing.status.failed.name');
        $this->push();

        $user->notify(new PaymentFailed());
    }

    /**
     * Cancel operation
     * @return void
     */
    public function paymentCancelled()
    {
        $this->status = config('billing.status.cancelled.name');
        $this->cancellation_at = now();
        $this->push();
    }

    /**
     * Payment expires
     * @return void
     */
    public function paymentExpired()
    {
        $this->status = config('billing.status.expired.name');
        $this->push();
    }

    /**
     * Retrieve the current transaction
     */
    public function transaction($transformer = TransactionTransformer::class)
    {
        $transaction = Transaction::where('code', $this->transaction_code)->first();
        return $transaction->meta($transformer);
    }
}
