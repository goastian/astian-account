<?php

namespace App\Transformers\Partner;

use App\Models\User\Partner;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class DataTransformer extends TransformerAbstract
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
    public function transform($partner)
    {
        return [
            'date' => $partner->date,
            'commission' =>  $this->formatMoney($partner->commission),
            'currency' => $partner->currency,
            'total' => $partner->total,
        ];
    }
}
