<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class WebController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show all data in array format
     * @param mixed $collection
     * @param mixed $transformer
     * @param mixed $code
     * @param mixed $pagination
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function showAllByBuilderArray(Builder $builder, $transformer = null, $code = 200, $pagination = true)
    {
        $collection = [];
        $per_page = (int) request()->has('per_page') ? request()->get('per_page') : 10;

        if ($pagination) {
            $collection = $builder->paginate($per_page);
        } else {
            $collection = $builder->get();
        }

        if ($transformer != null && gettype($transformer) != "integer") {
            $collection = fractal($collection, $transformer);
        }

        return $collection->toArray();
    }
}
