<?php

namespace App\Providers;

use App\Guard\TokenGuard;
use Laravel\Passport\Passport;
use App\Models\Setting\Setting;
use Illuminate\Support\Facades\Auth; 
use Laravel\Passport\ClientRepository;
use Illuminate\Support\ServiceProvider;
use League\OAuth2\Server\ResourceServer;
use Laravel\Passport\PassportUserProvider; 
use App\Models\OAuth\Bridge\AuthCodeRepository;
use App\Models\OAuth\Bridge\AccessTokenRepository;
use App\Http\Controllers\Web\OAuth\OpenId\DiscoveryController;
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

        //openID
        $this->app->bind(\OpenIDConnect\Laravel\DiscoveryController::class, DiscoveryController::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Setting::getDefaultSetting();

        Auth::extend('oauth2-passport-server', function ($app, $name, array $config) {
            return new TokenGuard(
                $this->app->make(ResourceServer::class),
                new PassportUserProvider(Auth::createUserProvider($config['provider']), $config['provider']),
                $this->app->make(ClientRepository::class),
                $this->app->make('encrypter'),
                $this->app->make('request')
            );
        });
    }
}
