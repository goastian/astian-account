<?php

namespace App\Providers;

use App\Guard\TokenGuard;
use App\Models\OAuth\Client;
use App\Models\OAuth\PersonalAccessClient;
use App\Models\OAuth\RefreshToken;
use App\Models\OAuth\Token;
use App\Models\User\Role;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\AuthCode;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Laravel\Passport\PassportUserProvider;
use Laravel\Passport\TokenRepository;
use League\OAuth2\Server\ResourceServer;

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
        if (env('SCHEME_MODE') == 'https') {
            URL::forceScheme(env('SCHEME_MODE'));
        }

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

        /**
         * deafult cookie name for laravel passport
         */
        Passport::cookie(env('COOKIE_NAME', 'auth_server'));

        /**
         * Custom models for laravel passport
         */
        Passport::useTokenModel(Token::class);
        Passport::useRefreshTokenModel(RefreshToken::class);
        Passport::useAuthCodeModel(AuthCode::class);
        Passport::useClientModel(Client::class);
        Passport::usePersonalAccessClientModel(PersonalAccessClient::class);

        /**
         * Custom time for laravel passport tokens
         */
        Passport::tokensExpireIn(now()->addSeconds(env('PASSPORT_TOKEN_EXPIRE')));
        Passport::refreshTokensExpireIn(now()->addSeconds(env('PASSPORT_REFRESH_EXPIRE')));
        Passport::personalAccessTokensExpireIn(now()->addDays(env('PASSPORT_PERSONAL_EXPIRE')));

        /**
         * default scopes for laravel passport
         */
        $scopes = [];

        try {
            foreach (Role::all() as $key => $value) {
                $scopes += array($value->name => $value->description);
            }
        } catch (QueryException $e) {}

        Passport::tokensCan($scopes);

    }
}
