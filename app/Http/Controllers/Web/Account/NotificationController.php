<?php
namespace App\Http\Controllers\Web\Account;

use App\Http\Controllers\WebController;
use App\Repositories\NotificationRepository;
use Inertia\Inertia;

class NotificationController extends WebController
{

    /**
     * 
     * @var NotificationRepository
     */
    public $repository;

    /**
     * Construct
     * @param \App\Repositories\NotificationRepository $notificationRepository
     */
    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->repository = $notificationRepository;
        $this->middleware('wants.json')->except('listAllNotifications');
    }

    public function listAllNotifications()
    {
        if (request()->wantsJson()) {
            return $this->repository->listAllNotifications();
        }

        return Inertia::render("Account/Notification/Index", [
            'route' => [
                'all' => route('users.notification.index'),
                'unread' => route('users.notification.unread')
            ],
        ]);
    }

    /**
     * List unread notifications
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function listUnreadNotifications()
    {
        return $this->repository->listUnreadNotifications();
    }

    /**
     * Show notification details
     * @param string $notification_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(string $notification_id)
    {
        return $this->repository->showNotification($notification_id);
    }

    /**
     * Mark as read notification
     * @param string $notification_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function markAsReadNotification(string $notification_id)
    {
        return $this->repository->markAsReadNotification($notification_id);
    }

    /**
     * Mark as read all notifications
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function markAsReadAllNotifications()
    {
        return $this->repository->markAsReadAllNotifications();
    }

    /**
     * Destroy all notifications
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroyNotifications()
    {
        return $this->repository->destroyNotifications();
    }
}
