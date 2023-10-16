<?php

namespace App\Providers;

use App\Models\User\Role;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Passport::ignoreRoutes();

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Passport::tokensExpireIn(now()->addHour(24));
        Passport::refreshTokensExpireIn(now()->addDays(5));
        Passport::personalAccessTokensExpireIn(now()->addDays(10));

        $scopes = [];

        foreach (Role::all() as $key => $value) {
            $scopes += array($value->name => $value->description);
        }

        Passport::tokensCan($scopes);

    }
}
