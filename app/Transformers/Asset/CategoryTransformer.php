<?php

namespace App\Transformers\Asset;

use App\Assets\Asset;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
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
            'categoria' => $data->name,
            'precio' => $data->price,
            'capacidad' => $data->capacity,
            'tv' => $data->tv,
            'baño_privado' => $data->bathroom,
            'aire_acondicionado' => $data->air_conditionar,
            'internet' => $data->wifi,
            'agua_caliente' => $data->hot_water,
            'agua_fria' => $data->cold_water,
            'ventilador' => $data->fan,
            'registrado' => $this->format_date($data->created_at),
            'actualizado' => $this->format_date($data->updated_at),
            'inactivo' => $this->format_date($data->deleted_at),
            'links' => [
                'parent' => route('categories.index'),
                'store' => route('categories.store'),
                'show' => route('categories.show', ['category' => $data->id]),
                'update' => route('categories.update', ['category' => $data->id]),
                'destroy' => route('categories.destroy', ['category' => $data->id]),
                'disable' => route('categories.disable', ['category' => $data->id]),
                'enable' => route('categories.enable', ['id' => $data->id]),
                'calendar' => route('categories.calendars.index', ['category' => $data->id])
            ]

        ];
    }


    public static function transformRequest($index)
    {
        $attribute = [ 
            'categoria' => 'name',
            'precio' => 'price',
            'capacidad' => 'capacity',
            'tv' => 'tv',
            'baño_privado' => 'bathroom',
            'aire_acondicionado' => 'air_conditionar',
            'internet' => 'wifi',
            'agua_caliente' => 'hot_water',
            'agua_fria' => 'cold_water',
            'ventilador' => 'fan'
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }


    public static function transformResponse($index)
    {
        $attribute = [ 
            'name' => 'categoria',
            'price' => 'precio',
            'capacity' => 'capacidad',
            'tv' => 'tv',
            'bathroom' => 'baño_privado',
            'air_conditionar' => 'aire_acondicionado',
            'wifi' => 'internet',
            'hot_water' => 'agua_caliente',
            'cold_water' => 'agua_fria',
            'fan' => 'ventilador'
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function getOriginalAttributes($index)
    {
        $attribute = [
            'id' => 'id',
            'categoria' => 'name',
            'precio' => 'price',
            'capacidad' => 'capacity',
            'tv' => 'tv',
            'baño_privado' => 'bathroom',
            'aire_acondicionado' => 'air_conditionar',
            'internet' => 'wifi',
            'agua_caliente' => 'hot_water',
            'ventilador' => 'fan',
            'agua_fria' => 'cold_water',
            'registrado' => 'created_at',
            'actualizado' => 'updated_at',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
