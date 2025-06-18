<?php
namespace App\Http\Controllers\Web\Account;

use DateTime;
use DateInterval;
use Inertia\Inertia;
use App\Models\User\User;
use App\Models\Setting\Code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\WebController;
use App\Http\Middleware\Auth2faMiddleware;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Elyerr\ApiResponse\Exceptions\ReportError;

class CodeController extends WebController
{
    use JsonResponser;

    public function __construct()
    {
        $this->middleware('auth:web')->only('requestToken2FA', 'factor2faEnableOrDisable');
    }

    /**
     * show view to insert a code 2FA
     *
     */
    public function create()
    {
        if (!request()->user()) {
            return view('factor.email');
        }

        return redirectToHome();
    }

    /**
     * Recovery token
     * @param \Illuminate\Http\Request $request
     * @return Code
     */
    public function getToken(Request $request)
    {
        $this->validate($request, [
            'token' => ['required'],
        ]);

        return Code::where('status', $request->session()->getId())->get()->last();
    }

    /**
     * Make login with 2FA token 
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|null
     */
    public function loginBy2FA(Request $request)
    {
        $code = $this->getToken($request);

        if (empty($code)) {
            return back()->with('error', __("An error occurred while processing your request. Please try again."));
        }

        $date = new DateTime($code->created_at);

        $date->add(new DateInterval("PT" . config('system.code_2fa_email_expires', 5) . "M"));
        $expire = $date->format('Y-m-d H:i:s');

        // Verify email
        if ($code->email != session()->get('email')) {
            return redirect()->route('login')
                ->with([
                    'status' => __('Email validation failed due to detected tampering. The process cannot continue.'),
                ]);
        }

        // Verify token
        if (!Hash::check($request->token, $code->code)) { 
            return back()->with([
                'warning' => __('Invalid verification code. Please check your email and try again.'),
            ]);
        }

        //Destroy expired token
        if (now() > $expire) {
            Code::destroyToken($code->status);
            return back()->with([
                'warning' => __('The authentication code is no longer valid. Please try again.'),
            ]);
        }

        //Login user by email
        Auth::login(User::where('email', $code->email)->first());

        // Remove token
        Code::destroyToken($code->status);

        //Remove email of the session
        session()->forget('email');

        return redirectToHome();
    }

    /**
     * Show the form to type code 2fa
     * @return \Inertia\Response
     */
    public function formToRequestToken()
    {
        return Inertia::render("Account/2fa");
    }

    /**
     * Send request to generate a new token
     * @param \Illuminate\Http\Request $request
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function requestToken2FA(Request $request)
    {
        $code = Code::where('status', $request->session()->getId())->get()->last();

        if ($code) {
            $date = new DateTime($code->created_at);
            $date->add(new DateInterval('PT' . config('system.code_2fa_email_expires', 5) . 'M'));
            $now = $date->format('Y-m-d H:i:s');

            if (now() < $now) {
                throw new ReportError(
                    __(
                        "Please wait a moment, the next token should be sent after :minute minutes",
                        ['minute' => config('system.code_2fa_email_expires', 5)]
                    ),
                    422
                );
            }
        }

        Auth2faMiddleware::generateToken($request);

        return $this->message(__('We have sent the token to your email'), 201);
    }

    /**
     * Send request to enable or disable 2FA authorization code
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function factor2faEnableOrDisable(Request $request)
    {
        $code = $this->getToken($request);

        $date = new DateTime($code->created_at);
        $date->add(new DateInterval("PT" . config('system.code_2fa_email_expires', 5) . "M"));
        $expire = $date->format('Y-m-d H:i:s');

        if (!Hash::check($request->token, $code->code)) {
            return $this->message(__('The token is incorrect.'));
        }

        if (now() > $expire) {
            return $this->message(__('Token expired'));
        }

        $user = User::find($request->user()->id);

        $user->m2fa = $user->m2fa ? 0 : 1;
        $user->push();

        Code::destroyToken($code->status);

        return $this->message(__($user->m2fa ? "2FA activated" : "2FA unactivated"), 201);
    }
}
