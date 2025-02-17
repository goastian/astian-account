<?php
namespace App\Http\Controllers\Setting;

use DateTime;
use DateInterval;
use App\Models\User\User;
use App\Models\Setting\Code;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use App\Providers\RouteServiceProvider;
use App\Http\Middleware\Auth2faMiddleware;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Elyerr\ApiResponse\Exceptions\ReportError;

class CodeController extends Controller
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

            $params = request()->all();

            return view('factor.email', ['query' => $params]);
        }

        return redirect(RouteServiceProvider::home());
    }

    /**
     * get token using session id
     *
     * @param Request $request
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
     * User authentication via 2FA
     *
     * @param Request $request
     */
    public function loginBy2FA(Request $request)
    {
        $code = $this->getToken($request);

        if (empty($code)) {
            return back()->with('error', __("An error occurred while processing your request. Please try again."));
        }

        $date = new DateTime($code->created_at);


        $date->add(new DateInterval("PT" . settingItem('code_2fa_email_expires', 5) . "M"));
        $expire = $date->format('Y-m-d H:i:s');

        if ($code->email != $request->email) {
            return redirect('login')->with([
                'status' => __('Avoid reloading the page before entering the code 2FA'),
            ]);
        }

        if (!Hash::check($request->token, $code->code)) {
            return redirect()->back()->with([
                'warning' => __('Token invalid'),
                'email' => $request->email,
            ]);
        }

        if (now() > $expire) {
            Code::destroyToken($code->status);
            return redirect()->back()->with([
                'warning' => __('Token expired'),
                'email' => $request->email,
            ]);
        }

        Auth::login(User::where('email', $code->email)->first());

        Code::destroyToken($code->status);

        return RouteServiceProvider::home();
    }

    /**
     * Send request to obtain 2FA activation token.
     *
     * @param Request $request
     * 
     * @return Json
     */
    public function requestToken2FA(Request $request)
    {
        $code = Code::where('status', $request->session()->getId())->get()->last();

        if ($code) {
            $date = new DateTime($code->created_at);
            $date->add(new DateInterval('PT' . settingItem('code_2fa_email_expires', 5) . 'M'));
            $now = $date->format('Y-m-d H:i:s');

            if (now() < $now) {
                throw new ReportError(__("Please wait a moment, the next token should be sent after " . date('H:i:s', strtotime($now))), 422);
            }
        }

        Auth2faMiddleware::generateToken($request);

        return $this->message(__('We have sent the token to your email'), 201);
    }

    /**
     * Authorize users to activate 2FA using a token.
     *
     * @param Request $request
     * @return Json
     */
    public function factor2faEnableOrDisable(Request $request)
    {
        $code = $this->getToken($request);

        $date = new DateTime($code->created_at);
        $date->add(new DateInterval("PT" . settingItem('code_2fa_email_expires', 5) . "M"));
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
