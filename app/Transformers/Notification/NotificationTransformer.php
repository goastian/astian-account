<?php

namespace App\Transformers\Notification;

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
                'recurso' => $data->resource,
                'links' => [
                    'parent' => route('notifications.index'),
                    'unread' => route('notifications.unread'),
                    'show' => route('notifications.show', ['notification' => $notification->id]),
                    'read' => route('notifications.read', ['notification' => $notification->id]),
                    'mark_as_read' => route('notifications.read_all'),
                    'destroy' => route('notifications.destroy'),
                ],
            ];

        } catch (ErrorException $e) {

            $data = json_decode($notification->data);

            return [
                'id' => $notification->id,
                'leido' => $notification->read_at,
                'titulo' => $data->title,
                'mensaje' => $data->message,
                'recurso' => $data->resource,
                'links' => [
                    'parent' => route('notifications.index'),
                    'unread' => route('notifications.unread'),
                    'show' => route('notifications.show', ['notification' => $notification->id]),
                    'read' => route('notifications.read', ['notification' => $notification->id]),
                    'mark_as_read' => route('notifications.read_all'),
                    'destroy' => route('notifications.destroy'),
                ],
            ];
        }
    }
}
