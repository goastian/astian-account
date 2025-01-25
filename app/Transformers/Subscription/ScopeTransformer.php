<?php
namespace App\Transformers\Subscription;


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
    public function transform($data)
    {
        return [
            'id' => $data->id,
            'service_id' => $data->service_id,
            'service_slug' => $data->service->slug,
            'role_id' => $data->role_id,
            'role_slug' => $data->role->slug,
            'requires_payment' => $data->requires_payment,
            'public' => $data->public,
            'active' => $data->active,
            'price' => number_format($data->price, 2),
            'scope' => $data->getScopeID(), 
            'created' => $this->format_date($data->created_at),
            'updated' => $this->format_date($data->updated_at),
            'links' => [
                'index' => route('scopes.index'),
                'store' => route('scopes.store'),
                'show' => route('scopes.show', ['scope' => $data->id]),
                'update' => route('scopes.update', ['scope' => $data->id]),
                'destroy' => route('scopes.destroy', ['scope' => $data->id]),
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
