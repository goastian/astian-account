<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
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
        $rateLimits = [
            'api' => [
                'limit' => config('rate_limit.general.api.limit'),
                'block_time' => config('rate_limit.general.api.block_time')
            ],
            'gateway' => [
                'limit' => config('rate_limit.general.gateway.limit'),
                'block_time' => config('rate_limit.general.gateway.block_time')
            ],
            'passport-token' => [
                'limit' => config('rate_limit.general.passport-token.limit'),
                'block_time' => config('rate_limit.general.passport-token.block_time')
            ],
            'default' => [
                'limit' => config('rate_limit.general.default.limit'),
                'block_time' => config('rate_limit.general.default.block_time')
            ],
            'broadcast' => [
                'limit' => config('rate_limit.general.broadcast.limit'),
                'block_time' => config('rate_limit.general.broadcast.block_time')
            ],
        ];

        foreach ($rateLimits as $key => $value) {
            RateLimiter::for($key, function (Request $request) use ($key, $value) {

                $cacheKey = 'rate-limit:' . $key . ':' . ($request->user()?->id ?: $request->ip());

                // Check if user is already blocked
                if (Cache::has($cacheKey . ':blocked')) {

                    $last_remaining = Cache::get($cacheKey . ':remaining_minutes', 1);

                    // Increase time to block user
                    $new_remaining_time = $last_remaining->addMinutes($value['block_time']);

                    // Clean current cache keys
                    Cache::forget($cacheKey . '::blocked');
                    Cache::forget($cacheKey . ':remaining_minutes');

                    // Update new keys
                    Cache::put(
                        $cacheKey . ':blocked',
                        true,
                        $new_remaining_time
                    );
                    Cache::put(
                        $cacheKey . ':remaining_minutes',
                        $new_remaining_time,
                        $new_remaining_time
                    );


                    Log::warning("Rate limit exceeded (blocked user kept trying)", [
                        'ip' => $request->ip(),
                        'path' => $request->path(),
                        'user_id' => $request->user()?->id,
                        'blocked_until' => $new_remaining_time,
                    ]);

                    return response()->json([
                        'message' => "Too many attempts. Your access is blocked until {$new_remaining_time} (UTC).",
                    ], 429);
                }

                // Set up the initial rate limit
                return Limit::perMinute($value['limit'])
                    ->by($request->user()?->id ?: $request->ip())
                    ->response(function (Request $request) use ($cacheKey, $value) {

                        $unlock_time = now()->addMinutes($value['block_time']);

                        Cache::put($cacheKey . ':blocked', true, $unlock_time);
                        Cache::put($cacheKey . ':remaining_minutes', $unlock_time, $unlock_time);

                        Log::warning("Rate limit exceeded (initial block)", [
                            'ip' => $request->ip(),
                            'path' => $request->path(),
                            'user_id' => $request->user()?->id,
                            'blocked_until' => $unlock_time->toDateTimeString()
                        ]);

                        return response()->json([
                            'message' => "Too many attempts. Your access is temporarily blocked until {$unlock_time} (UTC)."
                        ], 429);
                    });
            });
        }
    }
}
