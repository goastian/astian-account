<?php

namespace App\Listeners\Booking;

use App\Events\Booking\DeleteBookingEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DeleteBookingListener
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
     * @param  \App\Events\Booking\DeleteBookingEvent  $event
     * @return void
     */
    public function handle(DeleteBookingEvent $event)
    {
        //
    }
}