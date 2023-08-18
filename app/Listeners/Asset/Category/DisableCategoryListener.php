<?php

namespace App\Listeners\Asset\Category;

use App\Events\Asset\Category\DisableCategoryEvent;
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
     * @param  \App\Events\Asset\Category\DisableCategoryEvent  $event
     * @return void
     */
    public function handle(DisableCategoryEvent $event)
    {
        //
    }
}
