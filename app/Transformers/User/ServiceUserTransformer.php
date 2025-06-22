<?php
namespace App\Transformers\User;

use App\Models\Subscription\Service;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class ServiceUserTransformer extends TransformerAbstract
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
            'created' => $this->format_date($data->created_at),
            'updated' => $this->format_date($data->updated_at),
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
            'visibility' => 'visibility',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
