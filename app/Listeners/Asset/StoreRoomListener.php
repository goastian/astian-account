<?php

namespace App\Listeners\Asset;

use App\Events\Asset\StoreRoomEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreRoomListener
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
     * @param  \App\Events\Asset\StoreRoomEvent  $event
     * @return void
     */
    public function handle(StoreRoomEvent $event)
    {
        //
    }
}
