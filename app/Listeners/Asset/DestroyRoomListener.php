<?php

namespace App\Listeners\Asset;

use App\Events\Asset\DestroyRoomEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DestroyRoomListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Asset\DestroyRoomEvent  $event
     * @return void
     */
    public function handle(DestroyRoomEvent $event)
    {
        //
    }
}
