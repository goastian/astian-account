<?php

namespace App\Transformers\Payment;

use App\Assets\Asset;
use League\Fractal\TransformerAbstract;

class PaymentTransformer extends TransformerAbstract
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
                'booking' => route('booking.show', ['booking' => $this->getID($data->code)]),
            ],

        ];
    }

    public function getID($value)
    {
        $pattern = '/B(.*?)\-/';
        preg_match($pattern, $value, $matches);

        if (isset($matches[1])) {
            return $matches[1];
        } else {
            return null;
        }
    }

}
