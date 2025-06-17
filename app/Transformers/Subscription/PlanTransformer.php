<?php

namespace App\Transformers\Subscription;

use App\Models\Subscription\Plan;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class PlanTransformer extends TransformerAbstract
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
    public function transform(Plan $plan)
    {
        return [
            'id' => $plan->id,
            'name' => $plan->name,
            'slug' => $plan->slug,
            'description' => $plan->description,
            'public' => $plan->public ? true : false,
            'active' => $plan->active ? true : false,
            'bonus_enabled' => $plan->bonus_enabled ? true : false,
            'bonus_duration' => $plan->bonus_duration,
            'trial_enabled' => $plan->trial_enabled ? true : false,
            'trial_duration' => $plan->trial_duration,
            'created' => $this->format_date($plan->created_at),
            'updated' => $this->format_date($plan->updated_at),
            'scopes' => $plan->assignedScopes($plan->scopes),
            'prices' => $plan->transform($plan->prices, PlanPriceTransformer::class),
            'links' => [
                'parent' => route('admin.plans.index'),
                'store' => route('admin.plans.store'),
                'show' => route('admin.plans.show', ['plan' => $plan]),
                'update' => route('admin.plans.show', ['plan' => $plan]),
                'destroy' => route('admin.plans.show', ['plan' => $plan]),
            ],
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
            'id' => 'id',
            'name' => 'name',
            'created' => 'created_at',
            'updated' => 'updated_at'
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
