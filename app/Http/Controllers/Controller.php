<?php

namespace App\Http\Controllers;

use App\Traits\Standard;
use Elyerr\ApiResponse\Assets\Asset;
use Elyerr\EchoClient\Socket\Socket;
use Illuminate\Database\Eloquent\Builder;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, Socket, Standard, DispatchesJobs, ValidatesRequests, JsonResponser, Asset;

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

}
