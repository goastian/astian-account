<?php

namespace App\Providers;

use App\Models\User\Role;
use App\Models\OAuth\Token;
use App\Models\OAuth\Client;
use Laravel\Passport\AuthCode;
use Laravel\Passport\Passport;
use App\Models\OAuth\RefreshToken;
use Illuminate\Support\Facades\URL;
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
        if (env('SCHEME_MODE') == 'https') {
            URL::forceScheme(env('SCHEME_MODE'));
        }

        Passport::cookie(env('COOKIE_NAME', 'auth_server'));

        Passport::useTokenModel(Token::class);
        Passport::useRefreshTokenModel(RefreshToken::class);
        Passport::useAuthCodeModel(AuthCode::class);
        Passport::useClientModel(Client::class);
        Passport::usePersonalAccessClientModel(PersonalAccessClient::class);

        Passport::tokensExpireIn(now()->addSeconds(env('PASSPORT_TOKEN_EXPIRE')));
        Passport::refreshTokensExpireIn(now()->addDays(env('PASSPORT_REFRESH_EXPIRE')));
        Passport::personalAccessTokensExpireIn(now()->addDays(env('PASSPORT_PERSONAL_EXPIRE')));

        $scopes = [];

        foreach (Role::all() as $key => $value) {
            $scopes += array($value->name => $value->description);
        }

        Passport::tokensCan($scopes);

    }
}
