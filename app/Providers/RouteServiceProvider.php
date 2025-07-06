<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

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
            $limit = Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());

            Log::warning("Rate limit exceeded (api)", [
                'ip' => $request->ip(),
                'path' => $request->path(),
            ]);

            return $limit;
        });

        RateLimiter::for('gateway', function ($request) {
            $limit = Limit::perMinute(300)->by($request->ip());

            Log::warning("Rate limit exceeded (gateway)", [
                'ip' => $request->ip(),
                'path' => $request->path(),
            ]);

            return $limit;
        });

        RateLimiter::for('passport-token', function ($request) {
            $limit = Limit::perMinute(30)->by($request->ip());

            Log::warning("Rate limit exceeded (passport token)", [
                'ip' => $request->ip(),
                'path' => $request->path()
            ]);

            return $limit;
        });

        RateLimiter::for('default', function ($request) {
            $limit = Limit::perMinute(60)->by($request->ip());

            Log::warning("Rate limit exceeded (default)", [
                'ip' => $request->ip(),
                'path' => $request->path()
            ]);

            return $limit;
        });
    }

    /**
     * Redirect user after login
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|null
     */
    public static function home(Request $request)
    {
        return redirectToHome();
    }
}
