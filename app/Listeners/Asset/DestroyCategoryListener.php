<?php

namespace App\Listeners\Asset;

use App\Events\Asset\DestroyCategoryEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DestroyCategoryListener
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
     * @param  \App\Events\Asset\DestroyCategoryEvent  $event
     * @return void
     */
    public function handle(DestroyCategoryEvent $event)
    {
        //
    }
}
