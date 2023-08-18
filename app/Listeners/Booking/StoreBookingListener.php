<?php

namespace App\Listeners\Booking;

use App\Events\Booking\StoreBookingEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreBookingListener
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
     * @param  \App\Events\Booking\StoreBookingEvent  $event
     * @return void
     */
    public function handle(StoreBookingEvent $event)
    {
        //
    }
}
