<?php
namespace App\Transformers\User;

use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;
use App\Models\Subscription\Transaction;

class UserTransactionTransformer extends TransformerAbstract
{
    use Asset;
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            'id' => $transaction->id,
            'code' => $transaction->code,
            'currency' => $transaction->currency,
            'status' => $transaction->status,
            'tax_rate_id' => $transaction->tax_rate_id,
            'tax_percentage' => $transaction->tax_percentage,
            'tax_inclusive' => $transaction->tax_inclusive ? true : false,
            'tax_applied' => $transaction->tax_applied ? true : false,
            'subtotal' => $this->formatMoney($transaction->subtotal),
            'tax_amount' => $this->formatMoney($transaction->tax_amount),
            'total' => $this->formatMoney($transaction->total),
            'payment_method' => $transaction->payment_method,
            'billing_period' => $transaction->billing_period,
            'renew' => $transaction->renew ? true : false,
            'session_id' => $transaction->session_id,
            'payment_intent_id' => $transaction->payment_intent_id,
            'payment_url' => $transaction->package->isCancelled() ? null : $transaction->payment_url,
            'meta' => $transaction->meta,
            'created' => $this->format_date($transaction->created_at),
            'updated' => $this->format_date($transaction->updated_at),
            'links' => [
                'cancel' => route('users.subscriptions.cancel', ['transaction_id' => $transaction->id])
            ]
        ];
    }
}
