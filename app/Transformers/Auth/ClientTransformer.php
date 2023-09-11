<?php

namespace App\Transformers\Auth;

use App\Assets\Asset;
use League\Fractal\TransformerAbstract;

class ClientTransformer extends TransformerAbstract
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
            "nombre" => $data->name,
            "apellido" => $data->last_name,
            "documento" => $data->document,
            "numero" => $data->number,
            "ciudad" => $data->city,
            "pais" => $data->country,
            "correo_electronico" => $data->email,
            "telefono" => $data->phone,
            "registrado" => $this->format_date($data->created_at),
            "actualizado" => $this->format_date($data->updated_at),
        ];
    }

    public static function transformRequest($index)
    {
        $attribute = [
            "nombre" => 'name',
            "apellido" => 'last_name',
            "documento" => 'document',
            "numero" => 'number',
            "ciudad" => 'city',
            "pais" => 'country',
            "correo_electronico" => 'email',
            "telefono" => 'phone',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformResponse($index)
    {
        $attribute = [
            'name' => "nombre",
            'last_name' => "apellido",
            'document' => "documento",
            'number' => "numero",
            'city' => "ciudad",
            'country' => "pais",
            'email' => "correo_electronico",
            'phone' => "telefono",
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function getOriginalAttributes($index)
    {
        $attribute = [
            "id" => 'id',
            "nombre" => 'name',
            "apellido" => 'last_name',
            "documento" => 'document',
            "numero" => 'number',
            "ciudad" => 'city',
            "pais" => 'country',
            "correo_electronico" => 'email',
            "telefono" => 'phone',
            "registrado" => 'created_at',
            "actualizado" => 'updated_at',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
