<?php

namespace App\Listeners\Asset;

use App\Events\Asset\UpdateCategoryEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateCategoryListener
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
     * @param  \App\Events\Asset\UpdateCategoryEvent  $event
     * @return void
     */
    public function handle(UpdateCategoryEvent $event)
    {
        //
    }
}
