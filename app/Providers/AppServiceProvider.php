<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use App\Models\Setting\Setting;
use Illuminate\Support\ServiceProvider;
use App\Models\OAuth\Bridge\AuthCodeRepository;
use App\Models\OAuth\Bridge\AccessTokenRepository;
use Laravel\Passport\Bridge\AuthCodeRepository as LaravelAuthCodeRepository;
use Laravel\Passport\Bridge\AccessTokenRepository as LaravelAccessTokenRepository;

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

        //Override AuthCodeRepository  and AccessTokenRepository
        $this->app->bind(LaravelAuthCodeRepository::class, AuthCodeRepository::class);
        $this->app->bind(LaravelAccessTokenRepository::class, AccessTokenRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Setting::getDefaultSetting();
    }
}
