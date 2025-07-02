<?php
namespace App\Repositories;

use App\Transformers\Notification\NotificationTransformer;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Elyerr\ApiResponse\Exceptions\ReportError;


class NotificationRepository
{
    use JsonResponser;

    /**
     * List all notifications
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function listAllNotifications()
    {
        $data = request()->user()->readNotifications()
            ->latest()
            ->take(150)
            ->get();

        return $this->showAll($data, NotificationTransformer::class);
    }

    /**
     * List un read notifications
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function listUnreadNotifications()
    {
        $data = request()->user()->unreadNotifications()
            ->latest()
            ->take(150)
            ->get();

        return $this->showAll($data, NotificationTransformer::class);
    }

    /**
     * Show notification details
     * @param string $notification_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function showNotification(string $notification_id)
    {
        $notification = request()->user()->notifications()->where('id', $notification_id)->first();
        $this->markAsReadNotification($notification_id);
        return $this->showOne($notification, NotificationTransformer::class);
    }

    /**
     * Mark as read notification
     * @param string $notification_id
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function markAsReadNotification(string $notification_id)
    {
        $notification = request()->user()->notifications()->where('id', $notification_id)->first();

        if (empty($notification)) {
            throw new ReportError(__('Notification cannot be found'), 404);
        }

        $notification->markAsRead();

        return $this->message(__('Notification marked as read successfully'), 201);
    }

    /**
     * Mark all notifications as read
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function markAsReadAllNotifications()
    {
        request()->user()->unreadNotifications->markAsRead();
        return $this->message(__('Notifications marked as read successfully'), 201);
    }

    /**
     * Destroy all notification
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroyNotifications()
    {
        request()->user()->notifications()->delete();

        return $this->message(__('Notification deleted successfully'));
    }
}
