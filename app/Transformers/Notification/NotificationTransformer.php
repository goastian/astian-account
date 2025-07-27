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
            "title" => $data->title ?? null,
            "message" => $data->message ?? null,
            "link" => $data->url ?? null,
            "created" => $this->format_date($notification->created_at),
            "read_at" => $this->format_date($notification->read_at),
            'links' => [
                'index' => route('users.notification.index'),
                'destroy_all' => route('users.notification.destroy-all'),
                'mark_all_as_read' => route('users.notification.mark-all-as-read'),
                'unread' => route('users.notification.unread'),
                'show' => route('users.notification.show', ['notification_id' => $notification->id]),
                'mark_as_read' => route('users.notification.mark-as-read', ['notification_id' => $notification->id]),
            ],
        ];

    }
}
