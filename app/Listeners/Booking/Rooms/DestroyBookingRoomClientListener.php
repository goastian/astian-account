<?php

namespace App\Listeners\Booking\Rooms;

use App\Events\Booking\Rooms\DestroyBookingRoomClientEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DestroyBookingRoomClientListener
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
     * @param  \App\Events\Booking\Rooms\DestroyBookingRoomClientEvent  $event
     * @return void
     */
    public function handle(DestroyBookingRoomClientEvent $event)
    {
        //
    }
}
