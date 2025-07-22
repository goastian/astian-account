<?php
namespace App\Transformers\Subscription;

use App\Models\User\User;
use App\Models\Subscription\Package;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;
use App\Transformers\User\UserTransformer;

class PackageTransformer extends TransformerAbstract
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
    public function transform(Package $package)
    {
        return [
            'id' => $package->id,
            'start_at' => $this->format_date($package->start_at),
            'end_at' => $this->format_date($package->end_at),
            'cancellation_at' => $this->format_date($package->cancellation_at),
            'last_renewal_at' => $this->format_date($package->last_renewal_at),
            'is_recurring' => $package->is_recurring,
            'status' => $package->status,
            'transaction' => $package->transform($package->lastTransaction, TransactionTransformer::class),
            'transactions' => $package->transform($package->transactions, TransactionTransformer::class),
            'meta' => $package->meta, // save plan
            'user' => $package->transform($package->user, UserTransformer::class),
            'create' => $this->format_date($package->created_at),
            'updated' => $this->format_date($package->updated),
        ];
    }



    /**
     * Return the original attribute 
     * @param mixed $index
     * @return string|null
     */
    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'price' => 'price',
            'currency' => 'currency', 
            'billing_period' => 'billing_period',
            'payment_method' => 'payment_method',
            'start_at' => 'start_at',
            'end_at' => 'end_at',
            'cancellation_at' => 'cancellation_at',
            'last_renewal_at' => 'last_renewal_at',
            'meta' => 'meta',
            'is_recurring' => 'is_recurring',
            'status' => 'status',
            'create' => 'created_at',
            'updated' => 'updated_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
