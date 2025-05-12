<?php

namespace App\Providers;

use App\Guard\TokenGuard;
use Laravel\Passport\Passport;
use App\Models\Setting\Setting;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\ClientRepository;
use Illuminate\Support\ServiceProvider;
use League\OAuth2\Server\ResourceServer;
use Laravel\Passport\PassportUserProvider;

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
        Setting::getDefaultSetting();

        /**
         * Add new custom guard for laravel passport
         */
        Auth::extend('auth2', function ($app, $name, array $config) {
            return new TokenGuard(
                $this->app->make(ResourceServer::class),
                new PassportUserProvider(Auth::createUserProvider($config['provider']), $config['provider']),
                $this->app->make(TokenRepository::class),
                $this->app->make(ClientRepository::class),
                $this->app->make('encrypter'),
                $this->app->make('request')
            );
        });
    }
}
