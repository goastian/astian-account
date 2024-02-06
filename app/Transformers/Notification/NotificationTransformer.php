<?php

namespace App\Transformers\Notification;

use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class NotificationTransformer extends TransformerAbstract
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
    public function transform($notification)
    {
        $data = json_decode(json_encode($notification->data));

        return [
            'id' => $notification->id,
            'titulo' => $data->title,
            'mensaje' => $data->message,
            'recurso' => isset($data->resource) ? $data->resource : null,
            'leido' => $this->format_date($notification->read_at),
            'recibido' => $this->format_date($notification->created_at),
            'links' => [
                'parent' => route('notifications.index'),
                'unread' => route('notifications.unread'),
                'show' => route('notifications.show', ['notification' => $notification->id]),
                'read' => route('notifications.read', ['notification' => $notification->id]),
                'mark_as_read' => route('notifications.read_all'),
                'destroy' => route('notifications.destroy', ['notification' => $notification->id]),
                'clean' => route('notifications.clean'),
            ],
        ];

    }
}
