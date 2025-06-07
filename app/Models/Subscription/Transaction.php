<?php
namespace App\Models\Subscription;

use App\Models\Master;
use App\Models\User\User;
use Illuminate\Support\Str;
use App\Models\User\Partner;
use App\Models\Subscription\Package;
use App\Notifications\Subscription\PaymentFailed;
use App\Notifications\Subscription\RenewSuccessfully;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Notifications\Subscription\PaymentSuccessfully;
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
     * User
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a new session id
     * @return string
     */
    public static function generateSessionId()
    {
        return 'cs_offline_' . Str::random(45);
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
     * Partner
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Updated the transaction
     * @param array $meta
     * @return void
     */
    public static function paymentSuccessfully(array $meta, string $mode = 'session')
    {
        // Search transaction
        $transaction = Transaction::where(
            'code',
            $meta['metadata']['transaction_code']
        )->first();

        //customer 
        $customer = User::find($meta['metadata']['user_id']);

        //Page to redirect after payment
        $redirect_to = route('users.checkout.success', [
            "code" => $meta['metadata']['transaction_code']
        ]);

        switch ($mode) {
            case 'session':
                $transaction->payment_intent_id = $meta['payment_intent'];
                $transaction->session_id = $meta['id'];
                $transaction->user_id = auth()->check() ? auth()->user()->id : null;
                $transaction->push();

                break;

            case "succeed":
                $transaction->payment_method_id = $meta['payment_method'];
                $transaction->payment_intent_id = $meta['payment_intent'];
                $transaction->response = $meta;
                $transaction->status = config('billing.status.successful.name');
                $transaction->payment_url = $meta["receipt_url"];
                $transaction->user_id = auth()->check() ? auth()->user()->id : null;

                //Dispatch only renew packages
                if ($transaction->renew) {
                    $transaction->package->RenewSuccessfully($transaction->code);

                    //dispatch notification
                    $customer->notify(new RenewSuccessfully($redirect_to));

                } else {// Dispatch only buy packages
                    $transaction->package->paymentSuccessfully();

                    //Dispatch notification
                    $customer->notify(new PaymentSuccessfully($redirect_to));
                }

                //Set the package metadata
                $package_meta = $transaction->package->meta();
                unset($package_meta['transactions']);
                unset($package_meta['transaction']);
                unset($package_meta['user']);

                $transaction->meta = $package_meta;
                $transaction->push();
                break;
            default:
                break;
        }
    }

    /**
     * payment failed
     * @param array $meta
     * @return void
     */
    public static function paymentFailed(array $meta)
    {
        $transaction = Transaction::where(
            'code',
            $meta['metadata']['transaction_code']
        )->first();

        //customer 
        $customer = User::find($meta['metadata']['user_id']);

        //Page to redirect after payment
        $redirect_to = route('users.subscriptions.index');

        $transaction->status = config('billing.status.failed.name');
        $transaction->session_id = $meta['session']['id'];
        $transaction->payment_intent_id = $meta['id'];
        $transaction->payment_method_id = $meta['payment_method'];
        $transaction->response = $meta;
        $transaction->payment_url = $meta['session']['url'];
        //Set the package metadata
        $package_meta = $transaction->package->meta();
        unset($package_meta['transactions']);
        unset($package_meta['transaction']);
        unset($package_meta['user']);

        $transaction->meta = $package_meta;
        $transaction->push();

        $customer->notify(new PaymentFailed($redirect_to));
    }

    /**
     * Payment abort
     * @param Transaction $transaction
     * @return void
     */
    public static function paymentCancelled(Transaction $transaction)
    {
        $transaction->status = config('billing.status.cancelled.name');
        //Set the package metadata
        $package_meta = $transaction->package->meta();
        unset($package_meta['transactions']);
        unset($package_meta['transaction']);
        unset($package_meta['user']);

        $transaction->meta = $package_meta;
        $transaction->push();

        $transaction->package->paymentCancelled();
    }

    /**
     * Summary of paymentAbort
     * @param \App\Models\Subscription\Transaction $transaction
     * @return void
     */
    public static function paymentExpires(array $meta)
    {
        $transaction = Transaction::where(
            'code',
            $meta['metadata']['transaction_code']
        )->first();

        $transaction->status = config('billing.status.expired.name');
        $transaction->session_id = $meta['id'];
        $transaction->payment_intent_id = $meta['payment_intent'];
        $transaction->payment_url = $meta['url'];
        $transaction->response = $meta;
        $transaction->package->paymentExpires();

        $transaction->meta = $transaction->package->meta();
        $transaction->push();
    }

}
