<?php

namespace App\Listeners\Asset\Room;

use App\Events\Asset\Room\StoreRoomEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreRoomListener
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
     * @param  \App\Events\Asset\Room\StoreRoomEvent  $event
     * @return void
     */
    public function handle(StoreRoomEvent $event)
    {
        //
    }
}
