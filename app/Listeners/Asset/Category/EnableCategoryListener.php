<?php

namespace App\Listeners\Asset\Category;

use App\Events\Asset\Category\EnableCategoryEvent;
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
     * @param  \App\Events\Asset\Category\EnableCategoryEvent  $event
     * @return void
     */
    public function handle(EnableCategoryEvent $event)
    {
        //
    }
}
