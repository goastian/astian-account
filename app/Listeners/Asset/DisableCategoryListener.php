<?php

namespace App\Listeners\Asset;

use App\Events\Asset\DisableCategoryEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DisableCategoryListener
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
     * @param  \App\Events\Asset\DisableCategoryEvent  $event
     * @return void
     */
    public function handle(DisableCategoryEvent $event)
    {
        //
    }
}
