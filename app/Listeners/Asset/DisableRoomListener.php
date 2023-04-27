<?php

namespace App\Listeners\Asset;

use App\Events\Asset\DisableRoomEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DisableRoomListener
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
     * @param  \App\Events\Asset\DisableRoomEvent  $event
     * @return void
     */
    public function handle(DisableRoomEvent $event)
    {
        //
    }
}
