<?php
namespace App\Transformers\Partner;

use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;
use App\Models\Subscription\Transaction;

class TransactionTransformer extends TransformerAbstract
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
            'currency' => $transaction->currency,
            'status' => $transaction->status,
            'cents' => (int) $transaction->total,
            'partner_commission_rate' => floatval($transaction->partner_commission_rate),
            'total' => $this->formatMoney($transaction->total),
            'created' => $this->format_date($transaction->created_at),
            'updated' => $this->format_date($transaction->updated_at),
        ];
    }


    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'currency' => 'currency',
            'status' => 'status',
            'subtotal' => 'subtotal',
            'total' => 'total',
            'partner_commission_rate' => 'partner_commission_rate',
            'renew' => 'renew',
            'created' => 'created_at',
            'updated' => 'updated_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
