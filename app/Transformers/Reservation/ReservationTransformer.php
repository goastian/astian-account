<?php

namespace App\Transformers\Reservation;

use App\Assets\Asset;
use League\Fractal\TransformerAbstract;

class ReservationTransformer extends TransformerAbstract
{
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
    public function transform()
    {
        return [
            //
        ];
    }

    public static function transformRequest($index)
    {
        $index = Asset::changeIndex($index);

        $attribute = [
            'nombre' => 'name',
            'apellido' => 'last_name',
            'correo_electronio' => 'email',
            'telefono' => 'phone',
            'pais' => 'country',
            'ciudad' => 'city',

            'ingreso' => 'check_in',
            'salida' => 'check_out',

            'rooms' => 'rooms',
            'rooms.*.id' => 'rooms.*.id',
            'rooms.*.categoria' => 'rooms.*.category_id',
            'rooms.*.precio' => 'rooms.*.price',

            'empresa' => 'company',
            'ruc' => 'ruc',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformResponse($index)
    {
        $index = Asset::changeIndex($index);

        $attribute = [
            'name' => 'nombre',
            'last_name' => 'apellido',
            'email' => 'correo_electronio',
            'phone' => 'telefono',
            'country' => 'pais',
            'city' => 'ciudad',

            'check_in' => 'ingreso',
            'check_out' => 'salida',

            'rooms' => 'rooms',
            'rooms.*.id' => 'rooms.*.id',
            'rooms.*.category_id' => 'rooms.*.categoria',
            'rooms.*.price' => 'rooms.*.precio',

            'company' => 'empresa',
            'ruc' => 'ruc',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
