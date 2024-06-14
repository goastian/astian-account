<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\GlobalController as Controller;
use App\Models\Notify\Notification;
use App\Transformers\Notification\NotificationTransformer;
use Elyerr\ApiResponse\Exceptions\ReportError;

class UserNotificationController extends Controller
{
    /**
     * show all notification
     *
     * @return Object
     */
    public function index()
    {
        $notifications = request()->user()->notifications()->get();

        return $this->showAll($notifications, NotificationTransformer::class);
    }

    /**
     * show unread notificatios
     *
     * @return Object
     */
    public function show_unread_notifications()
    {
        $notifications = request()->user()->unreadNotifications()->get();

        return $this->showAll($notifications, NotificationTransformer::class);
    }

    /**
     * show a notification
     *
     * @return Json
     */
    public function show(Notification $notification)
    {
        throw_if(request()->user()->id != $notification->notifiable_id, new ReportError(__("The client does not have access rights to the content"), 403));

        return $this->showOne($notification, NotificationTransformer::class);
    }

    /**
     * mark as read a notification
     *
     * @return Json
     */
    public function mark_as_read_notification(Notification $notification)
    {
        throw_if(request()->user()->id != $notification->notifiable_id, new ReportError(__("The client does not have access rights to the content"), 403));

        $notification->read_at = now();
        $notification->push();

        //send event
        $this->privateChannel("ReadNotificationEvent", "Notification read");

        return $this->message(__('notification marked as read'), 201);
    }

    /**
     * mark as read all notifications
     *
     * @return Json
     */
    public function mark_as_read_notifications()
    {
        request()->user()->unreadNotifications->markAsRead();

        //send event
        $this->privateChannel("ReadNotificationEvent", "Notification read");

        return $this->message(__('notifications marked as read'), 201);
    }

    /**
     * destroy all notifications
     *
     * @return json
     */
    public function destroy(Notification $notification)
    {
        $notification = Notification::where('notifiable_id', request()->user()->id)->where('id', $notification->id)->first();

        $notification->delete();

        //send event
        $this->privateChannel("DestroyNotificationEvent", "Notification deleted");

        return $this->message(__('notifications removed'), 200);
    }

    /**
     * destroy all notifications
     *
     * @return json
     */
    public function clean()
    {
        request()->user()->notifications()->delete();

        //send event
        $this->privateChannel("DestroyNotificationEvent", "Notifications deleted");

        return $this->message(__('notifications removed'), 200);
    }
}
