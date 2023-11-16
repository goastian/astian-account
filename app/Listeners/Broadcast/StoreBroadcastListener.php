<?php

namespace App\Listeners\Broadcast;

use App\Events\Broadcast\StoreBroadcastEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreBroadcastListener
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
}
