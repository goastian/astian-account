<?php

namespace App\Transformers\Booking;

use League\Fractal\TransformerAbstract;

class BookingExtraChargeTransformer extends TransformerAbstract
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
            'cargo' => $data->charge,
            'cantidad' => $data->amount,
            'costo' => $data->price,
            'total' => $data->total,
            'registrado' => $data->created_at,
            'links' => [
                'previous' => route('booking.show', ['booking' => request('booking')->id]),
                'parent' => route('booking.charges.index', ['booking' => request('booking')->id]),
                'store' => route('booking.charges.store', ['booking' => request('booking')->id]),
                'destroy' => route('booking.charges.destroy', ['booking' => request('booking')->id, 'charge' => $data->id]),
            ]
        ];
    }

    public static function transformRequest($index)
    {
        $attribute = [
            'cargo' => 'charge',
            'cantidad' => 'amount',
            'precio' => 'price',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformResponse($index)
    {
        $attribute = [
            'charge' => 'cargo',
            'amount' => 'cantidad',
            'price' => 'precio',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function getOriginalAttributes($index)
    {
        $attribute = [
            'id' => 'id',
            'cargo' => 'charge',
            'cantidad' => 'amount',
            'costo' => 'price',
            'total' => 'total',
            'registrado' => 'created_at',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
