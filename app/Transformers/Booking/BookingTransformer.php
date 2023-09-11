<?php

namespace App\Transformers\Booking;

use App\Assets\Asset;
use App\Models\Booking\Booking;
use ErrorException;
use League\Fractal\TransformerAbstract;

class BookingTransformer extends TransformerAbstract
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
        if ($data instanceof Booking) {

            return [
                'id' => $data->id,
                'ingreso' => $this->format_date($data->check_in),
                'salida' => $this->format_date($data->check_out),
                'codigo' => $data->code,
                'empresa' => $data->company,
                'ruc' => $data->ruc,
                'type' => $data->type,
                'days' => $data->days,
                'cargos_adicionales' => $data->extra_charges,
                'habitaciones' => $data->total_room,
                'total' => $data->total,
                'por_pagar' => $data->to_pay,
                'pagado' => $data->paid,
                'reembolso' => $data->refund,
                'links' => [
                    'parent' => route('booking.index'),
                    'reservation' => route('reservations.store'),
                    'store' => route('booking.store'),
                    'show' => route('booking.show', ['booking' => $data->id]),
                    'update' => route('booking.update', ['booking' => $data->id]),
                    'destroy' => route('booking.destroy', ['booking' => $data->id]),
                    'rooms' => route('booking.rents.index', ['booking' => $data->id]),
                    'charges' => route('booking.charges.index', ['booking' => $data->id]),
                    'payment' => route('booking.payments.index', ['booking' => $data->id]),
                ]
            ];
        }

        return [
            'id' => $data->id,
            'codigo' => $data->code,
            'ingreso' => $this->format_date($data->check_in),
            'salida' => $this->format_date($data->check_out),
            'registrado' => $this->format_date($data->created_at),
            'actualizado' => $this->format_date($data->updated_at),
            'tipo' => $data->type,
            'nombre' => $data->name,
            'apellido' => $data->last_name, 
            'correo_electronico' => $data->email,
            'telefono' => $data->phone,
            'links' => [
                    'parent' => route('booking.index'),
                    'reservation' => route('reservations.store'),
                    'store' => route('booking.store'),
                    'show' => route('booking.show', ['booking' => $data->id]),
                    'update' => route('booking.update', ['booking' => $data->id]),
                    'destroy' => route('booking.destroy', ['booking' => $data->id]),
                    'rooms' => route('booking.rents.index', ['booking' => $data->id]),
                    'charges' => route('booking.charges.index', ['booking' => $data->id]),
                    'payment' => route('booking.payments.index', ['booking' => $data->id]),
                ]
        ];

    }

    public static function transformRequest($index)
    {
        $attribute = [
            'ingreso' => 'check_in',
            'salida' => 'check_out',
            'empresa' => 'company',
            'ruc' => 'ruc',
           /* 'rooms' => 'rooms',
            'rooms.*.id' => 'rooms.*.id',
            'rooms.*.categoria' => 'rooms.*.category_id',
            'rooms.*.precio' => 'rooms.*.price',*/
        ];

        // Manejar campos con valores numéricos
       /* if (preg_match('/rooms\.(\d+)\.id/', $index, $matches)) {
            $attribute[$index] = 'rooms.' . $matches[1] . '.id';
        }
        if (preg_match('/rooms\.(\d+)\.categoria/', $index, $matches)) {
            $attribute[$index] = 'rooms.' . $matches[1] . '.category_id';
        }
        if (preg_match('/rooms\.(\d+)\.precio/', $index, $matches)) {
            $attribute[$index] = 'rooms.' . $matches[1] . '.price';
        }*/

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformResponse($index)
    {
        $attribute = [
            'check_in' => 'ingreso',
            'check_out' => 'salida',
            'company' => 'empresa',
            'ruc' => 'ruc',
           /*'rooms' => 'rooms',
            'rooms.*.id' => 'rooms.*.id',
            'rooms.*.category_id' => 'rooms.*.categoria',
            'rooms.*.price' => 'rooms.*.precio',*/
        ];

        // Manejar campos con valores numéricos
        /*if (preg_match('/rooms\.(\d+)\.id/', $index, $matches)) {
            $attribute[$index] = 'rooms.' . $matches[1] . '.id';
        }
        if (preg_match('/rooms\.(\d+)\.category_id/', $index, $matches)) {
            $attribute[$index] = 'rooms.' . $matches[1] . '.categoria';
        }
        if (preg_match('/rooms\.(\d+)\.price/', $index, $matches)) {
            $attribute[$index] = 'rooms.' . $matches[1] . '.precio';
        }*/

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function getOriginalAttributes($index)
    {
        $attribute = [
            'id' => 'id',
            'ingreso' => 'check_in',
            'salida' => 'check_out',
            'codigo' => 'code',
            'habitacion' => 'room',
            'categoria' => 'category',
            'empresa' => 'company',
            'ruc' => 'ruc',
            'type' => 'type',
            'days' => 'days',
            'cargos_adicionales' => 'extra_charges',
            'habitaciones' => 'total_room',
            'total' => 'total',
            'por_pagar' => 'to_pay',
            'pagado' => 'paid',
            'reembolso' => 'refund',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
