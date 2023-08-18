<?php

namespace App\Listeners\Booking\Rooms;

use App\Events\Booking\Rooms\StoreBookingRoomEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreBookingRoomListener
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
     * @param  \App\Events\Booking\Rooms\StoreBookingRoomEvent  $event
     * @return void
     */
    public function handle(StoreBookingRoomEvent $event)
    {
        //
    }
}
