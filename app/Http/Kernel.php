<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\SecureHeaders::class,
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            //   \Laravel\Passport\Http\Middleware\CreateFreshApiToken::class,
            \App\Http\Middleware\VerifyAccount::class,
            \App\Http\Middleware\HandleInertiaRequests::class
        ],

        'api' => [
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\VerifyAccount::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        'transform.request' => \Elyerr\ApiResponse\Middleware\TransformRequest::class,
        'client' => \App\Http\Middleware\CheckClientCredentials::class,
        'scopes' => \App\Http\Middleware\CheckScopes::class,
        'scope' => \App\Http\Middleware\CheckForAnyScope::class,
        'wants.json' => \App\Http\Middleware\ResponseIsJson::class,
        'authorize' => \App\Http\Middleware\DenyGrantType::class,
        'verify.account' => \App\Http\Middleware\VerifyAccount::class,
        'verify.credentials' => \App\Http\Middleware\verifyCredentials::class,
        '2fa-mail' => \App\Http\Middleware\Auth2faMiddleware::class,
        'reactive.account' => \App\Http\Middleware\ReactiveAccount::class,
        'userCanAny' => \App\Http\Middleware\UserCanAny::class,
        'captcha' => \App\Http\Middleware\VerifyCaptcha::class,
    ];
}
