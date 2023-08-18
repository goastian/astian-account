<?php

namespace App\Listeners\Asset\Room;

use App\Events\Asset\Room\UpdateRoomEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateRoomListener
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
     * @param  \App\Events\Asset\Room\UpdateRoomEvent  $event
     * @return void
     */
    public function handle(UpdateRoomEvent $event)
    {
        //
    }
}
