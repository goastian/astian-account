<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Ckeckig autorization options
         */
        if (request()->header('Authorization')) {
            Broadcast::routes(['middleware' => ['auth:api']]);
        } else {
            Broadcast::routes();
        }

        require base_path('routes/channels.php');
    }
}
