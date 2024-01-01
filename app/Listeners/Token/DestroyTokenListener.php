<?php

namespace App\Listeners\Token;

use App\Events\Token\DestroyTokenEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DestroyTokenListener implements ShouldQueue
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
     * @param  \App\Events\Token\DestroyTokenEvent  $event
     * @return void
     */
    public function handle(DestroyTokenEvent $event)
    {
        //
    }
}
