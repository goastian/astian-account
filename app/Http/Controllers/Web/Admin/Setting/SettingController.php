<?php
namespace App\Http\Controllers\Web\Admin\Setting;

use App\Support\CacheKeys;
use Illuminate\Http\Request;
use App\Models\Setting\Setting;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\WebController;

class SettingController extends WebController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator:settings:full, administrator:settings:view')->except('update');
        $this->middleware('userCanAny:administrator:settings:full, administrator:settings:update')->only('update');
    }

    /**
     * Menu
     * @return \Illuminate\Contracts\View\View
     */
    public function menu()
    {
        return view('settings.setting');
    }

    /**
     * Update settings
     * @param \Illuminate\Http\Request $request
     * @return 
     */
    public function update(Request $request)
    {
        $data = $request->except('_method', '_token', 'current_route');

        $data = $this->transformRequest($data);

        foreach ($data as $key => $value) {
            settingAdd($key, $value);
        }

        return redirect($request->current_route)->with('status', __('Setting updated successfully'));
    }

    /**
     * Transform request
     * @param array $data
     * @param string $prefix
     * @return array
     */
    public function transformRequest(array $data, string $prefix = '')
    {
        $flattened = [];

        foreach ($data as $key => $value) {
            $newKey = $prefix ? "{$prefix}.{$key}" : $key;

            if (is_array($value)) {
                $flattened += $this->transformRequest($value, $newKey);
            } else {
                $flattened[$newKey] = $value;
            }
        }

        return $flattened;
    }

    /**
     * Reload cache for settings
     * @param \App\Models\Setting\Setting $setting
     * @return void
     */
    public function reloadCache(Request $request, Setting $setting)
    {
        $settings = $setting::all();

        foreach ($settings as $setting) {
            
            $cache_key = CacheKeys::settings($setting->key);
            
            Cache::forget($cache_key);
            
            Cache::put(
                $cache_key,
                $setting->value,
                now()->addDays(intval(config('cache.expires', 90)))
            );
        }

        return redirect($request->current_route)->with('status', __('Cache updated successfully'));
    }

    /**
     * Show the view of menu
     * @return \Illuminate\Contracts\View\View
     */
    public function general()
    {
        return view('settings.section.general');
    }

    /**
     * Show the view of email
     * @return \Illuminate\Contracts\View\View
     */
    public function email()
    {
        return view('settings.section.email');
    }

    /**
     * Show the view of 
     * @return \Illuminate\Contracts\View\View
     */
    public function routes()
    {
        return view('settings.section.routes');
    }

    /**
     * Show the view of 
     * @return \Illuminate\Contracts\View\View
     */
    public function redis()
    {
        return view('settings.section.redis');
    }

    /**
     * Show the view of 
     * @return \Illuminate\Contracts\View\View
     */
    public function queues()
    {
        return view('settings.section.queues');
    }

    /**
     * Show of cache view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function cache()
    {
        return view('settings.section.cache');
    }


    /**
     * Show the view of 
     * @return \Illuminate\Contracts\View\View
     */
    public function filesystem()
    {
        return view('settings.section.filesystem');
    }


    /**
     * Show the view of 
     * @return \Illuminate\Contracts\View\View
     */
    public function payment()
    {
        return view('settings.section.payment');
    }

    /**
     * Show the view of 
     * @return \Illuminate\Contracts\View\View
     */
    public function session()
    {
        return view('settings.section.session');
    }

    /**
     * Summary of security
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function security()
    {
        return view('settings.section.security');
    }
}
