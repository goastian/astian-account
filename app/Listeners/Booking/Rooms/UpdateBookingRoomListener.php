<?php

namespace App\Listeners\Booking\Rooms;

use App\Events\Booking\Rooms\UpdateBookingRoomEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateBookingRoomListener
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
     * @param  \App\Events\Booking\Rooms\UpdateBookingRoomEvent  $event
     * @return void
     */
    public function handle(UpdateBookingRoomEvent $event)
    {
        //
    }
}
