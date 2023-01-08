<?php

namespace App\Listeners\Token;

use App\Events\Token\DestroyAllTokenEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DestroyAllTokenListener
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
     * @param  \App\Events\Token\DestroyAllTokenEvent  $event
     * @return void
     */
    public function handle(DestroyAllTokenEvent $event)
    {
        //
    }
}
