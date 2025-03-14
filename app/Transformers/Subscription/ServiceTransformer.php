<?php
namespace App\Transformers\Subscription;

use App\Models\Subscription\Service;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class ServiceTransformer extends TransformerAbstract
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
    public function transform(Service $data)
    {
        return [
            'id' => $data->id,
            'name' => $data->name,
            'slug' => $data->slug,
            'description' => $data->description,
            'system' => $data->system ? true : false,
            'group_id' => $data->group_id,
            'group_name' => $data->group->name,
            'created' => $this->format_date($data->created_at),
            'updated' => $this->format_date($data->updated_at),
            'links' => [
                'index' => route('admin.services.index'),
                'store' => route('admin.services.store'),
                'show' => route('admin.services.show', ['service' => $data->id]),
                'update' => route('admin.services.update', ['service' => $data->id]),
                'destroy' => route('admin.services.destroy', ['service' => $data->id]),
                'scopes' => route('admin.services.scopes.index', ['service' => $data->id])
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
