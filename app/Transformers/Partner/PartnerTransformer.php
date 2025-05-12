<?php

namespace App\Transformers\Partner;

use App\Models\User\Partner;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class PartnerTransformer extends TransformerAbstract
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
    public function transform(Partner $partner)
    {
        return [
            'code' => $partner->code,
            'commission_rate' => $partner->commission_rate,
            'created' => $partner->created_at,
            'updated' => $partner->updated_at,
            'referral_links' => $partner->referLinks()
        ];
    }
}
