<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\StatefulGuard;
use League\OAuth2\Server\AuthorizationServer;
use Laravel\Passport\Bridge\AuthCodeRepository;
use App\Models\OAuth\Server\Grant\AuthCodeGrant;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use App\Http\Controllers\Web\OAuth\AuthorizationController;
use App\Repositories\OAuth\Server\Grant\OAuthSessionTokenRepository;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function register()
    {
        // Override AuthCodeGrant class
        $this->app->extend(
            AuthorizationServer::class,
            function (AuthorizationServer $server) {
                
                $grant = new AuthCodeGrant(
                    $this->app->make(AuthCodeRepository::class),
                    $this->app->make(RefreshTokenRepository::class),
                    new \DateInterval('PT10M'),
                    $this->app->make(OAuthSessionTokenRepository::class)
                );

                $server->enableGrantType(
                    $grant,
                    new \DateInterval('P1Y')
                );

                return $server;
            }
        );

    }


    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();


        // Override Authorization controller
        $this->app->when(AuthorizationController::class)
            ->needs(StatefulGuard::class)
            ->give(fn() => Auth::guard(config('passport.guard', null)));
    }
}
