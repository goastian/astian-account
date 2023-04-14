<?php

namespace App\Transformers\Asset;

use League\Fractal\TransformerAbstract;

class RoomTransformer extends TransformerAbstract
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
    public function transform($room)
    {
        return [
            'identificador' => $room->id,
            'numero' => $room->number, 
            'descripcion' => $room->description,
            'categoria_id' => $room->category_id,
            'categoria_nombre' => $room->category_name,
            'registrado' => $room->created_at,
            'actualizado' => $room->updated_at,
            'disponible' => $room->deleted_at,
        ];
    }


    public static function transformRequest($index)
    {
        $attribute = [
            'numero' => 'number',            
            'descripcion' => 'description', 
            'categoria_id' => 'category_id'
        ];
        
        return isset($attribute[$index]) ? $attribute[$index] : null;
    }


    public static function transformResponse($index)
    {
        $attribute = [
            'number' => 'numero', 
            'description' => 'descripcion', 
            'category_id' => 'categoria_id'
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function getOriginalAttributes($index)
    {
        $attribute = [
            'numero' => 'number', 
            'descripcion' => 'description',
            'categoria_id' => 'category_id',
            'categoria_nombre' => 'category_name',
            'registrado' => 'created_at',
            'actualizado' => 'updated_at',
            'disponible' => 'deleted_at',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
