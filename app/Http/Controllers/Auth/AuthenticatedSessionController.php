<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\GlobalController as Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use App\Transformers\Auth\EmployeeTransformer;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Elyerr\ApiResponse\Events\LoginEvent;
use Elyerr\ApiResponse\Events\LogoutEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    use JsonResponser;

    public function __construct()
    {
        $this->middleware('guest')->except('destroy', 'profile');
        $this->middleware('auth:api')->only('profile');
        $this->middleware('auth')->only('destroy');
    }

    public function create()
    {
        return view('auth.login');
        /*return Inertia::render('Auth/Login', [
    'reset_password' => route('password.email'),
    'login' => route('signin'),
    'token_name' => env('TOKEN_NAME'),
    'frontend' => env('FRONTEND_URL'),
    'domain' => env('SESSION_DOMAIN'),
    ]);*/
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        LoginEvent::dispatch(Auth::user());

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

        LogoutEvent::dispatch(Auth::user());
 
        return $request->wantsJson() ? route('login') : redirect(env('APP_URL'));
    }

    public function profile()
    {
        return $this->showOne(request()->user(), EmployeeTransformer::class);
    }
}
