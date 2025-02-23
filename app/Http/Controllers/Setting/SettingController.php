<?php
namespace App\Http\Controllers\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('userCanAny:administrator_admin_full');
    }

    /**
     * Menu
     * @return \Illuminate\Contracts\View\View
     */
    public function menu()
    {
        return view('settings.index');
    }

    /**
     * Update settings
     * @param \Illuminate\Http\Request $request
     * @return 
     */
    public function update(Request $request)
    {
        $data = $request->except('_method', '_token');

        $data = $this->transformRequest($data);

        foreach ($data as $key => $value) {
            settingAdd($key, $value);
        }

        return back()->with('status', __('Setting updated successfully'));
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
     * Show the view of menu
     * @return \Illuminate\Contracts\View\View
     */
    public function general()
    {
        return view('settings.section.general');
    }

    /**
     * Show the view of passport
     * @return \Illuminate\Contracts\View\View
     */
    public function passport()
    {
        return view('settings.section.passport');
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
     * Show the view of user
     * @return \Illuminate\Contracts\View\View
     */
    public function user()
    {
        return view('settings.section.user');
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
}
