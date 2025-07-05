<?php
namespace App\Http\Controllers\Web\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\WebController;
use App\Http\Requests\Auth\LoginRequest;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Elyerr\ApiResponse\Exceptions\ReportError;
use App\Repositories\OAuth\Server\Grant\OAuthSessionTokenRepository;

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
     * Show login form
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create(Request $request)
    {
        if (auth()->check()) {
            return redirectToHome();
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|null
     */
    public function store(LoginRequest $request)
    {
        // Redirect to
        $redirect_to = session()->get('redirect_to');

        // Delete session key
        session()->forget('redirect_to');

        $request->authenticate();

        $request->session()->regenerate();

        // Save the las connection
        auth()->user()->lastConnected();

        // Only json request
        if (request()->wantsJson()) {
            // data
            $data = [
                'message' => __("Login into account was successfully"),
                'user' => $this->authenticated_user(),
            ];

            // add page to redirect
            if (!empty($redirect_to)) {
                $data['redirect_to'] = $redirect_to;
            }

            return $this->data(['data' => $data]);
        }

        // Redirect to the origin url
        if (!empty($redirect_to)) {
            return redirect($redirect_to);
        }

        return redirectToHome();
    }

    /**
     * Destroy an authenticated session.
     * @param \Illuminate\Http\Request $request
     */
    public function destroy(Request $request, OAuthSessionTokenRepository $oAuthSessionTokenRepository)
    {

        if (!$request->isMethod('get') && !$request->isMethod('post')) {
            throw new ReportError("Method not allowed", 405);
        }

        // Save the last connected
        auth()->user()->lastConnected();

        // Destroy oauth sessions
        $oAuthSessionTokenRepository->destroyTokenSession(session()->getId());

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Added support for OpenID Connect
        if (!empty($request->post_logout_redirect_uri)) {
            return redirect($request->post_logout_redirect_uri);
        }

        return route('login');
    }
}
