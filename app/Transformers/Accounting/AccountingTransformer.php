<?php

namespace App\Transformers\Accounting;

use App\Assets\Asset;
use League\Fractal\TransformerAbstract;

class AccountingTransformer extends TransformerAbstract
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
            "id" => $data->id,
            "concepto" => $data->description,
            "monto" => $data->price,
            "tipo" => $data->type,
            'codigo' => $data->code,
            "metodo_pago" => $data->method,
            "registrado" => $this->format_date($data->created_at),
            "actualizado" => $this->format_date($data->updated_at), 
        ];
    }

    public static function transformRequest($index)
    {
        $attribute = [
            "concepto" => 'description',
            "monto" => 'price',
            'tipo' => 'type',
            'metodo_pago' => 'method',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformResponse($index)
    {
        $attribute = [
            'description' => "concepto",
            'price' => "monto",
            'type' => 'tipo',
            'method' => 'metodo_pago',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function getOriginalAttributes($index)
    {
        $attribute = [
            'id' => 'id',
            "concepto" => 'description',
            "monto" => 'price',
            "tipo" => 'type',
            "codigo" => 'code',
            "metodo_pago" => 'method',
            "registrado" => 'created_at',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public function getID($value)
    {
        $pattern = '/B(.*?)\-/';
        preg_match($pattern, $value, $matches);

        if (isset($matches[1])) {
            return $matches[1];
        } else {
            return null;
        }
    }
}
