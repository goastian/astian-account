<?php

namespace App\Http\Controllers\Auth;

use App\Assets\JsonResponser;
use App\Events\Auth\LoginEvent;
use App\Events\Auth\LogoutEvent;
use App\Http\Controllers\GlobalController as Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use App\Transformers\Auth\EmployeeTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthenticatedSessionController extends Controller
{
    use JsonResponser;

    public function __construct()
    {
        $this->middleware('guest')->except('destroy', 'profile');
        $this->middleware('auth:sanctum')->only('profile', 'destroy');
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

        LoginEvent::dispatch($this->AuthKey());

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

        LogoutEvent::dispatch($this->AuthKey());

        if (request()->wantsJson()) {
            return $this->message('La session ha terminado.');
        }

        return redirect(env('APP_URL'));
    }

    public function profile()
    {
        return $this->showOne(request()->user(), EmployeeTransformer::class);
    }
}
