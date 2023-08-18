<?php

namespace App\Listeners\Booking\Payments;

use App\Events\Booking\Payments\UpdateBookingPaymentEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateBookingPaymentListener
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
     * @param  \App\Events\Booking\Payments\UpdateBookingPaymentEvent  $event
     * @return void
     */
    public function handle(UpdateBookingPaymentEvent $event)
    {
        //
    }
}
