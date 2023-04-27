<?php

namespace App\Listeners\Asset;

use App\Events\Asset\StoreCategoryEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreCategoryListener
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
     * @param  \App\Events\Asset\StoreCategoryEvent  $event
     * @return void
     */
    public function handle(StoreCategoryEvent $event)
    {
        //
    }
}
