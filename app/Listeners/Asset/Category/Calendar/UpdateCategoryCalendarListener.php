<?php

namespace App\Listeners\Asset\Category\Calendar;

use App\Events\Asset\Category\Calendar\UpdateCategoryCalendarEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateCategoryCalendarListener
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
     * @param  \App\Events\Asset\Category\Calendar\UpdateCategoryCalendarEvent  $event
     * @return void
     */
    public function handle(UpdateCategoryCalendarEvent $event)
    {
        //
    }
}
