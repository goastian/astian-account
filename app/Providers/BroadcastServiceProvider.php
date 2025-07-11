<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (request()->header('Authorization')) {
            Broadcast::routes(['middleware' => ['throttle:broadcast', 'auth:api']]);
        } else {
            Broadcast::routes(['middleware' => ['throttle:broadcast', 'auth']]);
        }

        require base_path('routes/channels.php');
    }
}
