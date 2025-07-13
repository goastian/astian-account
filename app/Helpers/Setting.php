<?php

use App\Support\CacheKeys;
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
    function settingAdd($key, $value)
    {
        try {

            $cacheKey = CacheKeys::settings($key);

            if (CacheKeys::exceptKeys($key)) {
                Cache::forget($cacheKey);
                Cache::put($cacheKey, $value, now()->addDays(intval(config('cache.expires', 90))));
            }

            // Save database
            Setting::updateOrCreate(
                [
                    'key' => $key,
                ],
                [
                    'key' => $key,
                    'value' => $value,
                ]
            );
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
    function settingLoad($key, $value)
    {
        try {

            if (CacheKeys::exceptKeys($key)) {
                Cache::put(
                    CacheKeys::settings($key),
                    $value,
                    now()->addDays(intval(config('cache.expires', 90)))
                );
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
    function settingItem($key, $default = null)
    {
        try {

            if (CacheKeys::exceptKeys($key)) {

                $cacheKey = CacheKeys::settings($key);

                // Verify key and return if exists 
                if (Cache::has($cacheKey)) {
                    return Cache::get($cacheKey);
                }
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