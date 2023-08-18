<?php

namespace App\Transformers\Booking;

use League\Fractal\TransformerAbstract;

class BookingRentTransformer extends TransformerAbstract
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
    public function transform($data)
    {
        return [
            'id' => $data->id,
            'habitacion' => $data->room->number,
            'categoria' => $data->category->name,
            'precio' => $data->price,
            'registrado' => $data->created_at,
            'actualizado' => $data->updated_at,
            'links' => [
                'previous' => route('booking.show', ['booking' => request('booking')->id]),
                'parent' => route('booking.rooms.index', ['booking' => request('booking')->id]),
                'store' => route('booking.rooms.store', ['booking' => request('booking')->id]),
                'show' => route('booking.rooms.show', ['booking' => request('booking')->id, 'room' => $data->id]),
                'update' => route('booking.rooms.update', ['booking' => request('booking')->id, 'room' => $data->id]),
                'destroy' => route('booking.rooms.destroy', ['booking' => request('booking')->id, 'room' => $data->id]),
            ]   
        ];
    }

    public static function transformRequest($index)
    {
        $attribute = [
            'categoria_id' => 'category_id',
            'habitacion_id' => 'room_id',
            'precio' => 'price',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformResponse($index)
    {
        $attribute = [
            'category_id' => 'categoria_id',
            'room_id' => 'habitacion_id',
            'price' => 'precio',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function getOriginalAttributes($index)
    {
        $attribute = [
            'id' => 'id',
            'habitacion' => 'number',
            'categoria' => 'name',
            'precio' => 'price',
            'registrado' => 'created_at',
            'actualizado' => 'updated_at',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
