<?php

namespace App\Assets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

trait JsonResponser
{

    /**
     * muesta en mensaje en formato JSON
     * @param String $message
     * @param Integer $code
     */
    public function message($message, $code = 200)
    {
        return response()->json(['message' => $message], $code);
    }

    /**
     * muesta una collection en formato JSON
     * @param Collection $collection
     * @param Integer $code
     */
    public function data($collection, $code = 200)
    {
        return response()->json($collection, $code);
    }

    /**
     * muestra un objeto de un modelo
     * @param Model $model
     * @param Integer $code
     * @return Object
     */
    public function showOne(Model $model, $transformer = null, $code = 200)
    {
        if ($transformer != null && gettype($transformer) != "integer") {

            $model = fractal($model, $transformer);
        }

        return $this->data($model, $code);

    }

    /**
     * Muestra toda la collection
     * @param Model $model
     * @param $transformer
     * @param int $code
     * @return Collection
     */
    public function showAll(Collection $collection, $transformer = null, $code = 200)
    {
        $collection = $this->orderBy($collection);

        $collection = $this->paginate($collection);

        if ($transformer != null && gettype($transformer) != "integer") {
            $collection = fractal($collection, $transformer);
        }

        return $this->data($collection, $code);
    }

    /**
     * obtiene la claves o attributos de una clase
     * @param $table
     * @return Array
     */
    public function collumns_name_table($table)
    {
        $columns = Schema::getColumnListing($table);
        return $columns;

    }

    /**
     * pagina la informacion de una colleccion por defecto pagina cada 15 resultado
     * @param collection $collection
     * @return Collection
     *
     **/
    public function paginate(Collection $collection, $perPage = 15)
    {

        $rules = [
            'per_page' => 'integer|min:2',
        ];

        Validator::validate(request()->all(), $rules);

        $page = LengthAwarePaginator::resolveCurrentPage();

        if (request()->has('per_page')) {
            $perPage = (int) request()->per_page;
        }

        $result = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator($result, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        $paginated->appends(request()->all());

        return $paginated;
    }

    /**
     * transforma los parametros transformados en los campos de la tabla en BD
     * @param Model $model
     * @return Array
     */
    public function filter_transform($transformer)
    {
        $params = array();
        foreach (request()->all() as $index => $value) {
            if ($transformer::getOriginalAttributes($index)) {
                $params[$transformer::getOriginalAttributes($index)] = $value;
            }
        }

        return $params;
    }

    /**
     * Obtiene solo pararametros de una tabla para filtrar
     * @param String $table
     * @return Array
     */
    public function filter($table)
    {
        return request()->only($this->collumns_name_table($table));
    }

    /**
     * realiza la busqueda de data usando LIKE, requiere del modelo y los parametros a filtrar
     * @param Model $model
     * @param Array $params
     * @return Collection
     */
    public function search($table, array $params = null)
    {
        if (isset($params)) {
            foreach ($params as $key => $value) {
                return DB::table($table)->where($key, "LIKE", "%{$value}%")->get();
            }
        }

        return DB::table($table)->get();
    }

    /**
     * realiza una busque de datos con una tabla relacionada
     * @param String $table1
     * @param String $table2
     * @param String $attribute_rel
     * @param Array $params
     * @return Collection
     */
    public function searchWithRel(Model $model, $table2, $attribute_rel, array $params = null)
    {
        if (isset($params)) {
            foreach ($params as $key => $value) {
                return DB::table($table2)
                    ->where("{$attribute_rel}_id", "=", $model->id)
                    ->where($key, "LIKE", "%{$value}%")
                    ->get();
            }
        }

        return DB::table($table2)
            ->where("{$attribute_rel}_id", "=", $model->id)
            ->get();
    }

    /**
     * ordena la informacion a partir de una colleccion
     * @param Collection $collection
     */
    public function orderBy(Collection $collection)
    {
        //obtenemos los datos para ordenar
        $order_by = request()->only('order_by');
        $order_type = request()->only('order_type');

        if ($order_by) {

            //ordemos los valores
            foreach ($order_by as $key => $value) {
                if (isset($order_type['order_type']) and strtolower($order_type['order_type']) == "desc") {
                    $collection = $collection->sortByDesc($value);
                } else {
                    $collection = $collection->sortBy($value);
                }
            }

            $collection->values()->all();

            //retornamos la collection con los datos ordenados
            return collect($collection);

        } else {

            $sorted = $collection->sortDesc()->values()->all();
            return collect($sorted);
        }

    }
}
