<?php

namespace App\Listeners\Reservation;

use App\Events\Reservation\UpdateReservationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateReservationListener
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
     * @param  \App\Events\Reservation\UpdateReservationEvent  $event
     * @return void
     */
    public function handle(UpdateReservationEvent $event)
    {
        //
    }
}
