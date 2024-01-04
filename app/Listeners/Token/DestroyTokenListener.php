<?php

namespace App\Listeners\Token;

use App\Events\Token\DestroyTokenEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

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

    /**
     * Get the name of the listener's queue.
     */
    public function viaQueue(): string
    {
        return 'events';
    }
}
