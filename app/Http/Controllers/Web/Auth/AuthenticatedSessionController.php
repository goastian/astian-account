<?php
namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;
use Elyerr\ApiResponse\Assets\JsonResponser;

class AuthenticatedSessionController extends WebController
{
    use JsonResponser;

    public function __construct()
    {
        $this->middleware('auth')->only('destroy');
        $this->middleware('reactive.account')->only('store');
        $this->middleware('2fa-mail')->only('store');
    }

    /**
     * login del sistema
     */
    public function create()
    {
        if (auth()->check()) {
            return redirectToHome();
        }

        $params = request()->all();

        return view('auth.login', ['query' => $params]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();
        if ($request->module) {
            return redirect()->route('authorize.module', ['redirect_to' => $request->redirect_to]);
        }

        return RouteServiceProvider::home();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $request->wantsJson() ? route('login') : config('system.home_page', '/');
    }
}
