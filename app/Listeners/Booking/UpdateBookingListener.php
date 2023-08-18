<?php

namespace App\Listeners\Booking;

use App\Events\Booking\UpdateBookingEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateBookingListener
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
     * @param  \App\Events\Booking\UpdateBookingEvent  $event
     * @return void
     */
    public function handle(UpdateBookingEvent $event)
    {
        //
    }
}
