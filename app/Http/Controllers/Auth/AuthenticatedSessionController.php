<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\GlobalController as Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use App\Transformers\Auth\EmployeeTransformer;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    use JsonResponser;

    public function __construct()
    {
        $this->middleware('auth:api')->only('profile');
        $this->middleware('auth')->only('destroy');
        $this->middleware('reactive.account')->only('store');
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

        return $request->wantsJson() ? route('login') : redirect(env('APP_URL'));
    }

    public function profile()
    {
        return $this->showOne(request()->user(), EmployeeTransformer::class);
    }
}
