<?php

namespace App\Listeners\Booking\Client;

use App\Events\Booking\Client\DestroyBookingRoomClientEvent;
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
     * @param  \App\Events\Booking\Client\DestroyBookingRoomClientEvent  $event
     * @return void
     */
    public function handle(DestroyBookingRoomClientEvent $event)
    {
        //
    }
}
