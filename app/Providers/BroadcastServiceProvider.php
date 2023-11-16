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

        $middleware = request()->cookie(env('COOKIE_NAME', 'auth_server')) ?
        ['middleware' => ['web']] :
        ['middleware' => ['auth:api']];

        Broadcast::routes($middleware);

        require base_path('routes/channels.php');
    }
}
