<?php

namespace App\Listeners\Asset\Room;

use App\Events\Asset\Room\DisableRoomEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DisableRoomListener
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
     * @param  \App\Events\Asset\Room\DisableRoomEvent  $event
     * @return void
     */
    public function handle(DisableRoomEvent $event)
    {
        //
    }
}
