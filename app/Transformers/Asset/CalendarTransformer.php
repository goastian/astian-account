<?php

namespace App\Transformers\Asset;

use League\Fractal\TransformerAbstract;

class CalendarTransformer extends TransformerAbstract
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
    public static function transform($calendar)
    {
        return [
            'id' => $calendar->id,
            'dia' => $calendar->day,
            'disponibles' => $calendar->available,
            'categoria_id' => $calendar->category_id,
            'categoria' => $calendar->category_name
        ];
    }

    public static function transformRequest($index)
    {
        $attribute = [
            'dia' => 'day',
            'agregar_dias' => 'add_days',
            'disponibles' => 'available',
            'categoria_id' => 'category_id',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformResponse($index)
    {
        $attribute = [
            'day' => 'dia',
            'add_days' => 'agregar_dias',
            'available' => 'disponibles',
            'category_id' => 'categoria_id',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public  static function getOriginalAttributes($index)
    {
        $attribute = [
            'id' => 'id',
            'dia' => 'day',
            'disponibles' => 'available',
            'categoria_id' => 'category_id',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
