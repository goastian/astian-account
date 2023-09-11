<?php

namespace App\Listeners\Reservation;

use App\Events\Reservation\DestroyReservationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DestroyReservationListener
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
     * @param  \App\Events\Reservation\DestroyReservationEvent  $event
     * @return void
     */
    public function handle(DestroyReservationEvent $event)
    {
        //
    }
}
