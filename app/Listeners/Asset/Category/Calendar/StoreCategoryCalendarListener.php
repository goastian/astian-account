<?php

namespace App\Listeners\Asset\Category\Calendar;

use App\Events\Asset\Category\Calendar\StoreCategoryCalendarEvent;
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
     * @param  \App\Events\Asset\Category\Calendar\StoreCategoryCalendarEvent  $event
     * @return void
     */
    public function handle(StoreCategoryCalendarEvent $event)
    {
        //
    }
}
