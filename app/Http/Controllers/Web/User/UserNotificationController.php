<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\ApiController as Controller;
use App\Models\Notify\Notification;
use App\Transformers\Notification\NotificationTransformer;
use Elyerr\ApiResponse\Exceptions\ReportError;

class UserNotificationController extends Controller
{
    /**
     * show the all notifications of the users
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $notifications = request()->user()->notifications()->get();

        return $this->showAll($notifications, NotificationTransformer::class);
    }

    /**
     * Show the unread notification for the user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show_unread_notifications()
    {
        $notifications = request()->user()->unreadNotifications()->get();

        return $this->showAll($notifications, NotificationTransformer::class);
    }

    /**
     * Show detail of the notification
     * @param \App\Models\Notify\Notification $notification
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Notification $notification)
    {
        throw_if(request()->user()->id != $notification->notifiable_id, new ReportError(__("The client does not have access rights to the content"), 403));

        return $this->showOne($notification, NotificationTransformer::class);
    }

    /**
     * Mark as read the user notification
     * @param \App\Models\Notify\Notification $notification
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function mark_as_read_notification(Notification $notification)
    {
        throw_if(request()->user()->id != $notification->notifiable_id, new ReportError(__("The client does not have access rights to the content"), 403));

        $notification->read_at = now();
        $notification->push();

        //send event
        $this->privateChannel("ReadNotificationEvent", "Notification read successfully");

        return $this->message(__('notification marked as read'), 201);
    }

    /**
     * Mark as red the all notifications
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function mark_as_read_notifications()
    {
        request()->user()->unreadNotifications->markAsRead();

        //send event
        $this->privateChannel("ReadNotificationEvent", "Notification read");

        return $this->message(__('notifications marked as read'), 201);
    }

    /**
     * Destroy the notification
     * @param \App\Models\Notify\Notification $notification
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Notification $notification)
    {
        $notification = Notification::where('notifiable_id', request()->user()->id)->where('id', $notification->id)->first();

        $notification->delete();

        //send event
        $this->privateChannel("DestroyNotificationEvent", "Notification deleted");

        return $this->message(__('Notification deleted successfully'), 200);
    }

    /**
     * Destroy the all notifications
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function clean()
    {
        request()->user()->notifications()->delete();

        //send event
        $this->privateChannel("DestroyNotificationEvent", "Notifications deleted successfully");

        return $this->message(__('Notifications deleted successfully'), 200);
    }
}
