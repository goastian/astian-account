<?php

namespace App\Listeners\Asset\Category;

use App\Events\Asset\Category\StoreCategoryEvent;
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
     * @param  \App\Events\Asset\Category\StoreCategoryEvent  $event
     * @return void
     */
    public function handle(StoreCategoryEvent $event)
    {
        //
    }
}
