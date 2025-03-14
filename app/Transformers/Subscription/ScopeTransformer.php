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
            'service_id' => $data->service_id,
            'service_slug' => $data->service->slug,
            'service_description' => $data->service->description,
            'role_id' => $data->role_id,
            'role_slug' => $data->role->slug,
            'role_description' => $data->role->description,
            'public' => $data->public ? true : false,
            'active' => $data->active ? true : false,
            'gsr_id' => $data->getGsrID(),
            'created' => $this->format_date($data->created_at),
            'updated' => $this->format_date($data->updated_at),
            'links' => [
                'index' => route('admin.scopes.index'),
                'store' => route('admin.scopes.store'),
                'show' => route('admin.scopes.show', ['scope' => $data->id]),
                'update' => route('admin.scopes.update', ['scope' => $data->id]),
                'destroy' => route('admin.scopes.destroy', ['scope' => $data->id]),
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
