<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\GlobalController as Controller;
use App\Models\Notify\Notification;
use App\Transformers\Notification\NotificationTransformer;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Support\Facades\Lang;

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
        if (request()->user()->id != $notification->notifiable_id) {
            throw new ReportError(Lang::get('Unauthorize'), 403);
        }

        return $this->showOne($notification, NotificationTransformer::class);

    }

    /**
     * mark as read a notification
     * 
     * @return Json
     */
    public function mark_as_read_notification(Notification $notification)
    {
        if (request()->user()->id != $notification->notifiable_id) {
            throw new ReportError(Lang::get('Unauthorize'), 403);
        }

        $notification->read_at = now();
        $notification->push();

        return $this->message(Lang::get('notification marked as read'), 201);
    }

    /**
     * mark as read all notifications
     * 
     * @return Json
     */
    public function mark_as_read_notifications()
    {
        $notification = request()->user()->unreadNotifications->markAsRead();

        return $this->message(Lang::get('notifications marked as read'), 201);
    }

    /**
     * destroy all notifications
     * 
     * @return json
     */
    public function destroy()
    {
        request()->user()->notifications()->delete();

        return $this->message(Lang::get('notifications removed'), 200);
    }
}
