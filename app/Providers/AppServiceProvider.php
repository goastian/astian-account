<?php

namespace App\Providers;

use App\Models\User\Role;
use App\Models\OAuth\Token;
use App\Models\OAuth\Client;
use Laravel\Passport\AuthCode;
use Laravel\Passport\Passport;
use App\Models\OAuth\RefreshToken;
use Illuminate\Support\ServiceProvider;
use App\Models\OAuth\PersonalAccessClient;

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

        Passport::useTokenModel(Token::class);
        Passport::useRefreshTokenModel(RefreshToken::class);
        Passport::useAuthCodeModel(AuthCode::class);
        Passport::useClientModel(Client::class);
        Passport::usePersonalAccessClientModel(PersonalAccessClient::class);

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
