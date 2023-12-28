<?php

namespace App\Listeners\Broadcast;

use App\Events\Broadcast\StoreBroadcastEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreBroadcastListener implements ShouldQueue
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
    public function handle(StoreBroadcastEvent $event): void
    {
        //
    }

    /**
     * Get the name of the listener's queue.
     */
    public function viaQueue(): string
    {
        return env('REDIS_QUEUE_EVENTS', 'events');
    }
}
