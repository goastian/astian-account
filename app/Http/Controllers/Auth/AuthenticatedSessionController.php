<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Events\Auth\LoginEvent;
use App\Events\Auth\LogoutEvent;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;
use Elyerr\ApiResponse\Assets\JsonResponser;
use App\Transformers\Auth\EmployeeTransformer;
use App\Http\Controllers\GlobalController as Controller;

class AuthenticatedSessionController extends Controller
{
    use JsonResponser;

    public function __construct()
    {
        $this->middleware('auth:api')->only('profile');
        $this->middleware('auth')->only('destroy');
        $this->middleware('2fa-mail')->only('store');
    }

    /**
     * login del sistema
     */
    public function create()
    { 
        if (request()->user()) {
            return redirect(env('FRONTEND_URL'));
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

        LoginEvent::dispatch($this->authenticated_user());

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

        LogoutEvent::dispatch($this->authenticated_user());

        return $request->wantsJson() ? route('login') : redirect(env('APP_URL'));
    }

    public function profile()
    {
        return $this->showOne(request()->user(), EmployeeTransformer::class);
    }
}
