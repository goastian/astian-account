<?php

namespace App\Providers;

use App\Models\User\Role;
use Laravel\Passport\Passport;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
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
        
        
        $scopes =[];

        foreach (Role::all() as $key => $value) {
          array_push($scopes, [$value->name => $value->description]);
        }
        
        Passport::tokensCan($scopes);

    }
}
