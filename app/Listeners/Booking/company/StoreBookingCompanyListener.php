<?php

namespace App\Listeners\Booking\company;

use App\Events\Booking\company\StoreBookingCompanyEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreBookingCompanyListener
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
     * @param  \App\Events\Booking\company\StoreBookingCompanyEvent  $event
     * @return void
     */
    public function handle(StoreBookingCompanyEvent $event)
    {
        //
    }
}
