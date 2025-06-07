<?php
namespace App\Models\Subscription;

use App\Models\Master;
use App\Models\User\User;
use Elyerr\ApiResponse\Exceptions\ReportError;
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

    /**
     * Determine if the package renewal is still possible.
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return void
     */
    public function lastGracePeriodCheck()
    {
        $last_day = $this->end_at->addDays(config('billing.renew.grace_period_days'));

        if (now() > $last_day) {
            throw new ReportError(__("Renewal Failed: The request cannot be processed because the renewal date has already passed. Please contact support for further assistance."), 400);
        }
    }

    /**
     * Calc the expiration date
     * @return mixed
     */
    public function getEndDate()
    {
        // Retrieve plan metadata
        $meta = $this->meta;

        // Set the initial end date to the existing value or use the current date/time if not set
        $end_date = $this->end_at ?? now();

        // If this is the initial purchase (no end_at set)
        if (empty($this->end_at)) {

            // If a trial is enabled and has a duration, add it to the end date
            if ($meta['trial_enabled'] && $meta['trial_duration']) {
                $end_date->addDays($meta['trial_duration']);
            }

            // If a bonus is enabled and has a positive duration, add it to the end date
            if ($meta['bonus_enabled'] && $meta['bonus_duration'] > 0) {
                $end_date->addDays($meta['bonus_duration']);
            }
        }

        // If the subscription is being renewed (end_at is set)
        if (!empty($this->end_at)) {

            // Calculate the last valid day for renewal (grace period)
            $last_day = $this->end_at->addDays(config('billing.renew.grace_period_days'));

            // If we're within the renewal grace period and bonuses are enabled
            if (
                $last_day > now() && // still within the renewal window
                config("billing.renew.bonus_enabled") && // bonus on renewals is enabled globally
                $meta['bonus_enabled'] && // bonus is enabled in the plan
                $meta['bonus_duration'] > 0 // bonus duration is a positive number
            ) {
                $end_date->addDays($meta['bonus_duration']);
            }
        }

        // Finally, add the billing period duration to the end date
        $period = config('billing.period.' . $meta['price']['billing_period']);
        $unit = $period['unit']; // e.g., 'days', 'months'
        $interval = $period['interval']; // e.g., 1, 3, 6

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
        $this->start_at = now();
        $this->end_at = $this->getEndDate();
        $this->status = config('billing.status.successful.name');
        $this->push();

        //add payments scopes
        $this->addOrUpdatedScopeSubscription();
    }

    /**
     * Renew package
     * @param string $transaction_code
     * @return void
     */
    public function renewSuccessfully(string $transaction_code)
    {
        $this->end_at = $this->getEndDate();
        $this->status = config('billing.status.successful.name');
        $this->last_renewal_at = now();
        $this->transaction_code = $transaction_code;
        $this->push();

        //add payments scopes
        $this->addOrUpdatedScopeSubscription();
    }

    /**
     * Payment successfully
     * @return void
     */
    public function paymentFailed()
    {
        $this->status = config('billing.status.failed.name');
        $this->push();
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
}
