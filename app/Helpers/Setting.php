<?php

use App\Models\Setting\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('settingAdd')) {
    /**
     * Add or update an item
     * @param mixed $key
     * @param mixed $value
     * @param mixed $cache
     * @return void
     */
    function settingAdd($key, $value, $cache = true)
    {
        if ($cache) {
            Cache::forget($key);
        }

        try {
            Setting::updateOrCreate(
                [
                    'key' => $key,
                ],
                [
                    'key' => $key,
                    'value' => $value,
                ]
            );

            if ($cache) {
                cacheAdd($key, $value);
            }

        } catch (\Exception $th) {
        }
    }
}


if (!function_exists('settingLoad')) {
    /**
     * Add an item only if it does not exist
     * @param mixed $key
     * @param mixed $value
     * @param mixed $cache
     * @return void
     */
    function settingLoad($key, $value, $cache = true)
    {
        try {

            if ($cache) {
                cacheAdd($key, $value);
            }

            Setting::firstOrCreate(
                [
                    'key' => $key,
                ],
                [
                    'value' => $value
                ]
            );

        } catch (\Exception $th) {
        }
    }
}

if (!function_exists('settingItem')) {

    /**
     * Get the setting item
     * @param mixed $key
     * @param mixed $default
     * @param mixed $cache
     */
    function settingItem($key, $default = null, $cache = true)
    {
        try {
            if ($cache) {
                return cacheKey($key, function () use ($key, $default) {

                    $data = Setting::where('key', $key)->first();

                    return $data ? $data->value : $default;
                });
            }

            $data = Setting::where('key', $key)->first();
            return $data ? $data->value : $default;

        } catch (\Exception $e) {
        }
        return $default;
    }

}

if (!function_exists('redirectToHome')) {

    /**
     * Redirect to home user after login the user
     * @return Illuminate\Http\RedirectResponse|Illuminate\Routing\Redirector
     */
    function redirectToHome()
    {
        $url = config('app.url') . config('system.redirect_to', '/about');
        return redirect($url);
    }
}


if (!function_exists('cacheAdd')) {
    /**
     * add cache
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    function cacheAdd($key, $value)
    {
        $expires = now()->addDays(config('cache.expires'));
        Cache::forget($key);
        Cache::put(
            $key,
            $value,
            $expires
        );
    }
}


if (!function_exists('cacheKey')) {
    /**
     * add cache
     * @param mixed $key
     * @param mixed $callback
     * @return string
     */
    function cacheKey($key, $callback)
    {
        if (Cache::has($key)) {
            return Cache::get($key);
        }

        $expires = now()->addDays(config('cache.expires', 1));
        $value = $callback();
        Cache::put($key, $value, $expires);

        return $value;
    }
}