<?php
namespace App\Transformers\Subscription;

use App\Models\Subscription\Price;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class PlanPriceTransformer extends TransformerAbstract
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
    public function transform(Price $price)
    {
        return [
            'id' => $price->id,
            'billing_period' => $price->billing_period,
            'amount' => $price->amount,
            'expiration' => $this->format_date($price->billingPeriod()[$price->billing_period], 'Y-m-d H:i'),
            'links' => [
                'store' => route('admin.plans.prices.store', ['plan' => $price->priceable->id]),
                'destroy' => route('admin.plans.prices.destroy', [
                    'plan' => $price->priceable->id,
                    'price' => $price->id
                ])
            ]
        ];
    }
}
