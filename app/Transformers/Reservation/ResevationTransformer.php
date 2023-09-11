<?php

namespace App\Transformers\Reservation;

use App\Assets\Asset;
use App\Models\Booking\Booking;
use League\Fractal\TransformerAbstract;

class ResevationTransformer extends TransformerAbstract
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
                    'store' => route('reservation.store'),
                    'show' => route('booking.show', ['booking' => $data->id]),
                    'update' => route('booking.update', ['booking' => $data->id]),
                    'destroy' => route('booking.destroy', ['booking' => $data->id]),
                    'rooms' => route('booking.rents.index', ['booking' => $data->id]),
                    'charges' => route('booking.charges.index', ['booking' => $data->id]),
                    'payment' => route('booking.payments.index', ['booking' => $data->id]),
                ],
            ];
        }

        return [
            'id' => $data->id,
            'codigo' => $data->code,
            'ingreso' => $data->check_in,
            'salida' => $data->check_out,
            'registrado' => $data->created_at,
            'actualizado' => $data->updated_at,
            'tipo' => $data->type,
            'nombre' => $data->name,
            'apellido' => $data->last_name,
            'numero' => $data->number,
            'correo_electronico' => $data->number,
            'telefono' => $data->email,
            'links' => [
                'parent' => route('booking.index'),
                'store' => route('reservation.store'),
                'show' => route('booking.show', ['booking' => $data->id]),
                'update' => route('booking.update', ['booking' => $data->id]),
                'destroy' => route('booking.destroy', ['booking' => $data->id]),
                'rooms' => route('booking.rents.index', ['booking' => $data->id]),
                'charges' => route('booking.charges.index', ['booking' => $data->id]),
                'payment' => route('booking.payments.index', ['booking' => $data->id]),
            ],
        ];

    }
}
