<?php
namespace App\Transformers\Subscription;


use App\Models\Subscription\Scope;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class ScopeTransformer extends TransformerAbstract
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
            'api_key' => $data->api_key ? true : false,
            'gsr_id' => $data->getGsrId(),
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
                'index' => route('admin.scopes.index'),
            ]
        ];
    }

    /**
     * Return the original keys
     * @param mixed $index
     * @return string|null
     */
    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'name' => "name",
            'slug' => "slug",
            'description' => "service",
            'system' => "system",
            'group_id' => "group_id",
            'created' => "created_at",
            'updated' => "updated_at",
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
