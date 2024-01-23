<?php

namespace App\Listeners\Notification;

use App\Events\Notification\ReadNotificationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReadNotificationListener implements ShouldQueue
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
    public function handle(ReadNotificationEvent $event): void
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
