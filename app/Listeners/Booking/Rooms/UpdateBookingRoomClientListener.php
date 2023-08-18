<?php

namespace App\Listeners\Booking\Rooms;

use App\Events\Booking\Rooms\UpdateBookingRoomClientEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateBookingRoomClientListener
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
     * @param  \App\Events\Booking\Rooms\UpdateBookingRoomClientEvent  $event
     * @return void
     */
    public function handle(UpdateBookingRoomClientEvent $event)
    {
        //
    }
}
