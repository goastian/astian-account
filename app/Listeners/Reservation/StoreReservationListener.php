<?php

namespace App\Listeners\Reservation;

use App\Events\Reservation\StoreReservationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreReservationListener
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
     * @param  \App\Events\Reservation\StoreReservationEvent  $event
     * @return void
     */
    public function handle(StoreReservationEvent $event)
    {
        //
    }
}
