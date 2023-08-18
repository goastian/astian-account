<?php

namespace App\Listeners\Booking;

use App\Events\Booking\updateBookingEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class updateBookingListener
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
     * @param  \App\Events\Booking\updateBookingEvent  $event
     * @return void
     */
    public function handle(updateBookingEvent $event)
    {
        //
    }
}
