<?php

namespace App\Listeners\Asset;

use App\Events\Asset\EnableRoomEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EnableRoomListener
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
     * @param  \App\Events\Asset\EnableRoomEvent  $event
     * @return void
     */
    public function handle(EnableRoomEvent $event)
    {
        //
    }
}
