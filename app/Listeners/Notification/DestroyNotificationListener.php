<?php

namespace App\Listeners\Notification;

use App\Events\Notification\DestroyNotificationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DestroyNotificationListener implements ShouldQueue
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
    public function handle(DestroyNotificationEvent $event): void
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
