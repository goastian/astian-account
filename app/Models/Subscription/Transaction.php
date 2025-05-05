<?php
namespace App\Models\Subscription;

use App\Models\Master;
use Illuminate\Support\Str;
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
    ];

    protected $casts = [
        'response' => 'array',
        'renew' => 'boolean',
        'tax_inclusive' => 'boolean',
        'tax_applied' => 'boolean',
        'meta' => 'array'
    ];

    /**
     * Generate a transaction code
     * @return string
     */
    public static function generateTransactionCode()
    {
        $micro = explode(' ', microtime());
        $timestamp = date('YmdHis', (int) $micro[1]) . substr($micro[0], 2, 3);
        return 'TXN-' . $timestamp . '-' . strtoupper(Str::random(4));
    }

    /**
     * Generate a new session id
     * @return string
     */
    public static function generateSessionId()
    {
        return 'cs_test_offline_' . Str::random(45);
    }

    /**
     * Generate a intent code
     * @return string
     */
    public static function generateIntent()
    {
        return 'pi_' . Str::random(45);
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
     * Updated the transaction
     * @param array $meta
     * @return void
     */
    public static function paymentSuccessfully(array $meta)
    {
        $transaction = Transaction::where('session_id', $meta['id'])->first();
        $transaction->status = config('billing.status.successful.name');
        $transaction->payment_intent_id = $meta['payment_intent'];
        $transaction->response = $meta;

        if ($transaction->renew) {
            $transaction->package->renewSuccessfully();
        } else {
            $transaction->package->paymentSuccessfully();
        }

        $transaction->meta = $transaction->package->meta();
        $transaction->push();
    }

    /**
     * payment failed
     * @param array $meta
     * @return void
     */
    public static function paymentFailed(array $meta)
    {
        $transaction = Transaction::where('session_id', $meta['id'])->first();
        $transaction->status = config('billing.status.failed.name');
        $transaction->payment_intent_id = $meta['payment_intent'];
        $transaction->response = $meta;
        $transaction->meta = $transaction->package->meta();
        $transaction->push();
    }

    /**
     * Payment abort
     * @param Transaction $transaction
     * @return void
     */
    public static function paymentCancelled(Transaction $transaction)
    {
        $transaction->status = config('billing.status.cancelled.name');
        $transaction->meta = $transaction->package->meta();
        $transaction->push();

        $transaction->package->paymentCancelled();
    }

    /**
     * Summary of paymentAbort
     * @param \App\Models\Subscription\Transaction $transaction
     * @return void
     */
    public static function paymentExpires(string $session_id)
    {
        $transaction = Transaction::where('session_id', $session_id);
        // if ($transaction->status != config('billing.status.successful.name')) {
        $transaction->status = config('billing.status.expired.name');
        $transaction->package->paymentExpires();

        $transaction->meta = $transaction->package->meta();
        $transaction->push();
        //}
    }

}
