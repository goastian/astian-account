<?php

namespace App\Transformers\Asset;
  
use Elyerr\ApiExtend\Assets\Asset;
use League\Fractal\TransformerAbstract;

class RoomTransformer extends TransformerAbstract
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
            'numero' => $data->number, 
            'capacidad' => $data->capacity,
            'descripcion' => $data->description,
            'nota' => $data->note, 
            'categoria_id' => $data->category_id,
            'categoria_nombre' => $data->category_name ? $data->category_name : $data->category->name,
            'registrado' => $this->format_date($data->created_at),
            'actualizado' => $this->format_date($data->updated_at),
            'inactivo' => $this->format_date($data->deleted_at),
            'links' => [
                'parent' => route('rooms.index'),
                'store' => route('rooms.store'),
                'show' => route('rooms.show', ['room' => $data->id]),
                'update' => route('rooms.update', ['room' => $data->id]),
                'destroy' => route('rooms.update', ['room' => $data->id]),
                'disable' => route('rooms.disable', ['room' => $data->id]),
                'enable' => route('rooms.enable', ['id' => $data->id]),
            ]
        ];
    }


    public static function transformRequest($index)
    {
        $attribute = [
            'numero' => 'number',            
            'capacidad' => 'capacity',            
            'descripcion' => 'description', 
            'nota' => 'note', 
            'categoria_id' => 'category_id'
        ];
        
        return isset($attribute[$index]) ? $attribute[$index] : null;
    }


    public static function transformResponse($index)
    {
        $attribute = [
            'number' => 'numero', 
            'capacity' => 'capacidad', 
            'description' => 'descripcion', 
            'note' => 'nota', 
            'category_id' => 'categoria_id'
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function getOriginalAttributes($index)
    {
        $attribute = [
            'numero' => 'number', 
            'capacidad' => 'capacity', 
            'descripcion' => 'description',
            'nota' => 'note',
            'categoria_id' => 'category_id',
            'categoria_nombre' => 'category_name',
            'registrado' => 'created_at',
            'actualizado' => 'updated_at',
            'inactivo' => 'deleted_at',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
