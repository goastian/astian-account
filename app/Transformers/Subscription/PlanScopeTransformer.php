<?php
namespace App\Transformers\Subscription;


use App\Models\Subscription\Plan;
use App\Models\Subscription\Scope;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class PlanScopeTransformer extends TransformerAbstract
{
    use Asset;

    /**
     * Plan 
     * @var 
     */
    public $plan;

    public function __construct(Plan $plan)
    {
        $this->plan = $plan;
    }

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
    public function transform(Scope $scope)
    {
        return [
            'id' => $scope->id,
            'public' => $scope->public ? true : false,
            'active' => $scope->active ? true : false,
            'gsr_id' => $scope->getGsrId(),
            'api_key' => $scope->api_key,
            'service' => [
                'id' => $scope->service->id,
                'name' => $scope->service->name,
                'slug' => $scope->service->slug,
                'description' => $scope->service->description,
                'system' => $scope->service->system ? true : false,
                'group' => [
                    'id' => $scope->service->group->id,
                    'name' => $scope->service->group->name,
                    'description' => $scope->service->group->description,
                ]
            ],
            'role' => [
                'id' => $scope->role->id,
                'name' => $scope->role->name,
                'slug' => $scope->role->slug,
                'description' => $scope->role->description
            ],
            'created' => $this->format_date($scope->created_at),
            'updated' => $this->format_date($scope->updated_at),
            'links' => [
                'revoke' => route('admin.plans.scopes.revoke', ['plan' => $this->plan->id, 'scope' => $scope->id]),
            ]
        ];
    }
}
