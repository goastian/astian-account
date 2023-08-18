<?php

namespace App\Listeners\Booking\company;

use App\Events\Booking\company\UpdateBookingCompanyEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateBookingCompanyListener
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
     * @param  \App\Events\Booking\company\UpdateBookingCompanyEvent  $event
     * @return void
     */
    public function handle(UpdateBookingCompanyEvent $event)
    {
        //
    }
}
