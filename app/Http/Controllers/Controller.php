<?php

namespace App\Http\Controllers;

use App\Traits\Standard;
use Elyerr\ApiResponse\Assets\Asset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Transformers\User\AuthTransformer;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests,
        Standard,
        DispatchesJobs,
        ValidatesRequests,
        JsonResponser,
        Asset;

    /**
     * Order by collection using params order_by and order_type
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param mixed $transformer
     * @return Builder
     */
    public function orderByBuilder(Builder $builder, $transformer = null)
    {
        $order_by = request()->order_by;
        $order_type = request()->order_type ?? 'desc';

        if (!in_array(strtolower($order_type), ['asc', 'desc'])) {
            $order_type = 'asc';
        }

        if ($transformer) {
            if (method_exists($transformer, 'getOriginalAttributes') && $order_by) {
                $order_by = $transformer::getOriginalAttributes($order_by);
            }
        } else {
            $columns = $builder->getQuery()->getConnection()->getSchemaBuilder()->getColumnListing($builder->getQuery()->from);

            if (!in_array($order_by, $columns)) {
                $order_by = null;
            }
        }

        if ($order_by) {
            $builder->orderBy($order_by, $order_type);
        } else {
            $builder->orderBy('id', $order_type);
        }

        return $builder;
    }


    /**
     * Return information about the current users and transform date in the process
     * @return mixed
     */
    public function authenticated_user()
    {
        $user = fractal(Auth::user(), AuthTransformer::class);

        return json_decode(json_encode($user))->data;
    }

    /**
     * search by builder
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $params
     * @return Builder
     */
    public function searchByBuilder(Builder $query, array $params)
    {
        foreach ($params as $key => $value) {
            if (!isset($value) || trim($value) === '') {
                continue;
            }

            $query->whereRaw("LOWER({$key}) LIKE ?", ['%' . strtolower($value) . '%']);
        }

        return $query;
    }
}
