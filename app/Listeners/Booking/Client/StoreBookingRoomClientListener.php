<?php

namespace App\Listeners\Booking\Client;

use App\Events\Booking\Client\StoreBookingRoomClientEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreBookingRoomClientListener
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
     * @param  \App\Events\Booking\Client\StoreBookingRoomClientEvent  $event
     * @return void
     */
    public function handle(StoreBookingRoomClientEvent $event)
    {
        //
    }
}
