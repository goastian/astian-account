<?php

namespace App\Listeners\Asset;

use App\Events\Asset\StoreCategoryCalendarEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreCategoryCalendarListener
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
     * @param  \App\Events\Asset\StoreCategoryCalendarEvent  $event
     * @return void
     */
    public function handle(StoreCategoryCalendarEvent $event)
    {
        //
    }
}
