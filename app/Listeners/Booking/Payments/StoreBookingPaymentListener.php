<?php

namespace App\Listeners\Booking\Payments;

use App\Events\Booking\Payments\StoreBookingPaymentEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreBookingPaymentListener
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
     * @param  \App\Events\Booking\Payments\StoreBookingPaymentEvent  $event
     * @return void
     */
    public function handle(StoreBookingPaymentEvent $event)
    {
        //
    }
}
