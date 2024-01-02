<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

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

    public static function home()
    {
        if (RouteServiceProvider::query()) {
            return RouteServiceProvider::redirectToAskForAuthorization();
        }

        return redirect(env('FRONTEND_URL'));
    }

    /**
     * redirecciona a una vista para que el cliente seleccione los scopes
     * o permisos que desea otorgarle al cliente
     * @param  \Illuminate\Http\Request  $request
     * @param Closure $next
     * @return null
     */
    public static function redirectToAskForAuthorization()
    {
        $query = http_build_query(RouteServiceProvider::query());

        return redirect(env('APP_URL') . '/oauth/authorize?' . $query);
    }

    /**
     * recupera los parametros de la url
     * @return Array
     */
    public static function query()
    {
        return request()->except(['_token', 'email', 'password','token']);
    }

    /**
     *
     */
    public static function redirectToLogin()
    {
        $params = RouteServiceProvider::query();

        return redirect()->route('login', $params);
    }
}
