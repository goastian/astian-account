<?php

namespace App\Listeners\Asset;

use App\Events\Asset\EnableCategoryEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EnableCategoryListener
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
     * @param  \App\Events\Asset\EnableCategoryEvent  $event
     * @return void
     */
    public function handle(EnableCategoryEvent $event)
    {
        //
    }
}
