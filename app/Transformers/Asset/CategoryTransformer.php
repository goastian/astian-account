<?php

namespace App\Transformers\Asset;

use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
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
    public function transform($category)
    { 
        return [
            'identificador' => $category->id,
            'categoria' => $category->name,
            'precio' => $category->price,
            'tv' => $category->tv,
            'baño_privado' => $category->bathroom,
            'aire_acondicionado' => $category->air_conditionar,
            'internet' => $category->wifi,
            'agua_caliente' => $category->hot_water,
            'agua_fria' => $category->cold_water,
            'ventilador' => $category->fan,
            'registrado' => $category->created_at,
            'actualizado' => $category->updated_at,
            'inactivo' => $category->deleted_at,

        ];
    }


    public static function transformRequest($index)
    {
        $attribute = [ 
            'categoria' => 'name',
            'precio' => 'price',
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
            'identificador' => 'id',
            'categoria' => 'name',
            'precio' => 'price',
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
