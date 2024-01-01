<?php

namespace App\Listeners\Broadcast;

use App\Events\Broadcast\DestroyBroadcastEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DestroyBroadcastListener implements ShouldQueue
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
    public function handle(DestroyBroadcastEvent $event): void
    {
        //
    }
}
