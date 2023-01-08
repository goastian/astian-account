<?php

namespace App\Listeners\Token;

use App\Events\Token\DestoryTokenEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DestoryTokenListener
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
     * @param  \App\Events\Token\DestoryTokenEvent  $event
     * @return void
     */
    public function handle(DestoryTokenEvent $event)
    {
        //
    }
}
