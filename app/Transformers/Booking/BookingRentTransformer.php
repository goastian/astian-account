<?php

namespace App\Transformers\Booking;

use App\Assets\Asset;
use League\Fractal\TransformerAbstract;

class BookingRentTransformer extends TransformerAbstract
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
            'habitacion' => $data->room ? $data->room->number : null,
            'categoria' => $data->category->name,
            'precio' => $data->price,
            'registrado' => $this->format_date($data->created_at),
            'actualizado' => $this->format_date($data->updated_at),
            'links' => [
                'previous' => route('booking.show', ['booking' => request('booking')->id]),
                'parent' => route('booking.rents.index', ['booking' => request('booking')->id]),
                'store' => route('booking.rents.store', ['booking' => request('booking')->id]),
                'show' => route('booking.rents.show', ['booking' => request('booking')->id, 'rent' => $data->id]),
                'update' => route('booking.rents.update', ['booking' => request('booking')->id, 'rent' => $data->id]),
                'destroy' => route('booking.rents.destroy', ['booking' => request('booking')->id, 'rent' => $data->id]),
                'guest' => route('booking.rents.huespeds.index', ['booking' => request('booking')->id, 'rent' => $data->id]),
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
