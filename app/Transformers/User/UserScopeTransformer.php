<?php
namespace App\Transformers\User;

use App\Models\User\UserScope;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class UserScopeTransformer extends TransformerAbstract
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
            'group' => $data->scope->service->group->slug,
            'service' => $data->scope->service->slug,
            'role' => $data->scope->role->slug,
            'scope' => $data->scope->getGsrID(),
            'public' => $data->scope->public,
            'active' => $data->scope->active,
            'end_date' => $data->end_date,
            'package_id' => $data->package_id, 
            'created_at' => $this->format_date($data->created_at),
            'updated_at' => $this->format_date($data->updated_at),
            'links' => [
                'index' => route('admin.users.scopes.index', ['user' => $data->user_id]),
                'assign' => route('admin.users.scopes.assign', ['user' => $data->user_id]),
                'revoke' => route('admin.users.scopes.revoke', ['user' => $data->user_id]), 
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
            'scope_id' => 'scope_id',
            'user_id' => 'user_id',
            'end_date' => 'end_date',
            'package_id' => 'package_id', 
            'created' => "created_at",
            'updated' => "updated_at",
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
