<?php

namespace App\Listeners\Asset;

use App\Events\Asset\UpdateCategoryCalendarEvent;
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
     * @param  \App\Events\Asset\UpdateCategoryCalendarEvent  $event
     * @return void
     */
    public function handle(UpdateCategoryCalendarEvent $event)
    {
        //
    }
}
