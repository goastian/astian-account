<?php

use App\Models\Setting\Setting;

if (!function_exists('settingAdd')) {
    /**
     * Add or update an item
     * @param mixed $key
     * @param mixed $value
     * @param mixed $user
     * @return void
     */
    function settingAdd($key, $value, $user = false)
    {
        $user = $user ? auth()->user()->id : null;
        try {
            Setting::updateOrCreate(
                [
                    'key' => $key,
                    'user_id' => $user
                ],
                [
                    'key' => $key,
                    'value' => $value,
                    'user_id' => $user,
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
     * @param mixed $user
     * @return void
     */
    function settingLoad($key, $value, $user = false)
    {
        $user = $user ? auth()->user()->id : null;
        try {
            Setting::firstOrCreate(
                [
                    'key' => $key,
                    'user_id' => $user
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
     * @param mixed $user
     */
    function settingItem($key, $default = null, $user = false)
    {
        try {
            $userId = $user ? auth()->user()->id : null;

            $query = Setting::query();

            $query->where('key', $key)->where(function ($subQuery) use ($userId) {
                if ($userId) {
                    $subQuery->where('user_id', $userId);
                } else {
                    $subQuery->whereNull('user_id');
                }
            });

            $setting = $query->first();

            return $setting ? $setting->value : $default;

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