<?php
namespace App\Transformers\Subscription;


use App\Models\Subscription\Scope;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class ServiceScopeTransformer extends TransformerAbstract
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
    public function transform(Scope $data)
    {
        return [
            'id' => $data->id,
            'public' => $data->public ? true : false,
            'active' => $data->active ? true : false,
            'gsr_id' => $data->getGsrID(),
            'api_key' => $data->api_key,
            'service' => [
                'id' => $data->service->id,
                'name' => $data->service->name,
                'slug' => $data->service->slug,
                'description' => $data->service->description,
                'system' => $data->service->system ? true : false,
                'group' => [
                    'id' => $data->service->group->id,
                    'name' => $data->service->group->name,
                    'description' => $data->service->group->description,
                ]
            ],
            'role' => [
                'id' => $data->role->id,
                'name' => $data->role->name,
                'slug' => $data->role->slug,
                'description' => $data->role->description
            ],
            'created' => $this->format_date($data->created_at),
            'updated' => $this->format_date($data->updated_at),
            'links' => [
                'index' => route('admin.service.scopes.index', ['service' => $data->service->id]),
                'assign' => route('admin.service.scopes.assign', ['service' => $data->service->id]),
                'revoke' => route('admin.services.scopes.revoke', [
                    'service' => $data->service->id,
                    'scope' => $data->id,
                ])
            ]
        ];
    }
}
