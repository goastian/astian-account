<?php

namespace App\Transformers\Notification;

use TypeError;
use ErrorException;
use League\Fractal\TransformerAbstract;

class NotificationTransformer extends TransformerAbstract
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
    public function transform($notification)
    {
        try {
            $data = json_decode(json_encode($notification->data));

            return [
                'id' => $notification->id,
                'leido' => $notification->read_at,
                'titulo' => $data->title,
                'mensaje' => $data->message,
                'link' => $data->resource,
            ];

        } catch (ErrorException $e) {

            $data = json_decode($notification->data);

            return [
                'id' => $notification->id,
                'leido' => $notification->read_at,
                'titulo' => $data->title,
                'mensaje' => $data->message,
                'link' => $data->resource,
            ];
        }
    }
}
