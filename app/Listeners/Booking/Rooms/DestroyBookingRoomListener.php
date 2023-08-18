<?php

namespace App\Listeners\Booking\Rooms;

use App\Events\Booking\Rooms\DestroyBookingRoomEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DestroyBookingRoomListener
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
     * @param  \App\Events\Booking\Rooms\DestroyBookingRoomEvent  $event
     * @return void
     */
    public function handle(DestroyBookingRoomEvent $event)
    {
        //
    }
}
