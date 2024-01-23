<?php

namespace App\Listeners\Notification;

use App\Events\Notification\PushNotificationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PushNotificationListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PushNotificationEvent $event): void
    {
        //
    }

     /**
     * Get the name of the listener's queue.
     */
    public function viaQueue(): string
    {
        return 'events';
    }
}
