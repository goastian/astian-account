<?php

namespace App\Transformers\Booking;

use App\Assets\Asset;
use League\Fractal\TransformerAbstract;

class BookingPaymentTransformer extends TransformerAbstract
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
            "concepto" => $data->description,
            "precio" => $data->price,
            "codigo" => $data->code,
            "tipo" => $data->type == 'in' ? "ingreso" : 'egreso',
            "forma_pago" => $data->method,
            "realizado" => $this->format_date($data->created_at),
            "actualizado" => $this->format_date($data->updated_at),
            'links' => [
                'previous' => route('booking.show', ['booking' => request('booking')->id]),
                'parent' => route('booking.payments.index', ['booking' => request('booking')->id]),
                'store' => route('booking.payments.store', ['booking' => request('booking')->id]),
                'update' => route('booking.payments.update', ['booking' => request('booking')->id , 'payment' => $data->id]),

            ],
        ];
    }

    public static function transformRequest($index)
    {
        $attribute = [
            'precio' => 'price',
            'concepto' => 'description',
            'tipo' => 'type',
            'forma_pago' => 'method',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformResponse($index)
    {
        $attribute = [
            'price' => 'precio',
            'description' => 'concepto',
            'type' => 'tipo',
            'method' => 'forma_pago',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function getOriginalAttributes($index)
    {
        $attribute = [
            "id" => 'id',
            "concepto" => 'description',
            "precio" => 'price',
            "codigo" => 'code',
            "tipo" => 'type',
            "forma_pago" => 'method',
            "realizado" => 'created_at',
            "actualizado" => 'updated_at',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
