<?php

namespace App\Assets;

use ErrorException;
use App\Exceptions\ReportMessage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

trait JsonResponser
{

    /**
     * muesta en mensaje en formato JSON
     * @param mixed $collection
     * @param Integer $code
     */
    public function message($collection, $code = 200)
    {
        return response()->json($collection, $code);
    }

    /**
     * muestra un objeto de un modelo
     * @param Model $model
     * @param Integer $code
     * @return Object
     */
    public function showOne(Model $model, $code = 200)
    { 
        return $this->message($model, $code);
    }

    /**
     * Muestra toda la collection
     * @param Model $model
     * @param $transformer 
     * @param int $code
     * @return Collection
     */
    public function showAll(Collection $collection, $code = 200)
    {
        //si no tiene data la $colleccion enviamos un mensjae
        // if (count($collection) == 0) {
        //     throw new ReportMessage(__("No Data"), 404);
        // }

        $collection = $this->orderBy($collection);

        $collection = $this->paginate($collection);

        return $this->message($collection, $code);
    }


    /**
     * obtiene la claves o attributos de una clase
     * @param Model $model
     * @return Array
     */
    public function getKeyParamas(Model $model)
    {
        $params = [];

        if (!$model->first()) {
            throw new ReportMessage(__("No Data"), 404);
        }

        foreach ($model->getAttributes() as $key => $value) {
            $params[] = $key;
        }

        return $params;
    }


    /**
     * pagina la informacion
     * @param collection $collection
     * @return Collection
     *
     **/
    public function paginate(Collection $collection)
    {

        $rules = [
            'per_page' => 'integer|min:2',
        ];

        Validator::validate(request()->all(), $rules);

        $page = LengthAwarePaginator::resolveCurrentPage();

        $perPage = 7;


        if (request()->has('per_page')) {
            $perPage = (int)request()->per_page;
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
    public function transformFilter($transformer)
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
     * realiza la busqueda de data usando LIKE, requiere del modelo y los parametros a filtrar
     * @param Model $model
     * @param Array $params
     * @return Collection
     */
    public function search($table, array $params = null)
    {
        if (isset($params)) {
            foreach ($params as $key => $value) {
                return  DB::table($table)->where($key, "LIKE", "%{$value}%")->get();
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
     * @param $transformer
     */
    public function orderBy(Collection $collection)
    {
        //obtenemos los datos para ordenar
        $order_by = request()->only('order_by');

        try {
                       //ordemos los valores
            foreach ($order_by as $key => $value) {

                $collection = $collection->sortBy([$key, $value]);
            }

            $collection->values()->all();

            //retornamos la collection con los datos ordenados
            return collect($collection);
        } catch (ErrorException $th) {

            $sorted = $collection->sortDesc()->values()->all();
            return  collect($sorted);
        }
    }    
}
