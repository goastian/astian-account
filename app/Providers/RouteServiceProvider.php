<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{

    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    /**
     * Redirect user after login
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|null
     */
    public static function home()
    {
        if (RouteServiceProvider::query()) {
            return RouteServiceProvider::redirectToAskForAuthorization();
        }
        return  redirectToHome();
    }

    /**
     * Redirect to the user after login to authorize third party application
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public static function redirectToAskForAuthorization()
    {
        $query = http_build_query(RouteServiceProvider::query());

        return redirect(config('app.url') . '/oauth/authorize?' . $query);
    }

    /**
     * Get the query params
     * @return array
     */
    public static function query()
    {
        return request()->except(['_token', 'email', 'password', 'token']);
    }

    /**
     * Redirect to the login if the user is not authenticatable
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public static function redirectToLogin()
    {
        $params = RouteServiceProvider::query();

        return redirect()->route('login', $params);
    }
}
