<?php

namespace App\Http\Controllers\Factor;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Auth2faMiddleware;
use App\Models\Factor\Code;
use App\Models\User\Employee;
use App\Providers\RouteServiceProvider;
use DateInterval;
use DateTime;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;

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
     * User autentication via 2FA
     *
     * @param Request $request
     */
    public function loginBy2FA(Request $request)
    {
        $code = $this->getToken($request);

        $date = new DateTime($code->created_at);
        $date->add(new DateInterval("PT" . env('CODE_2FA_EXPIRE') . "M"));
        $expire = $date->format('Y-m-d H:i:s');

        if ($code->email != $request->email) {
            return redirect('login')->with([
                'status' => Lang::get('Evite recargar la pagina antes de ingresar el codigo 2FA'),
            ]);
        }

        if (!Hash::check($request->token, $code->code)) {
            return redirect()->back()->with([
                'warning' => Lang::get('El token es incorrecto.'),
                'email' => $request->email,
            ]);
        }

        if (now() > $expire) {
            Code::destroyToken($code->status);
            return redirect()->back()->with([
                'warning' => Lang::get('El token proporciondado ha caducado'),
                'email' => $request->email,
            ]);
        }

        Auth::login(Employee::where('email', $code->email)->first());

        Code::destroyToken($code->status);

        return RouteServiceProvider::home();
    }

    /**
     * send request to get a token to activate 2FA
     *
     * @param Request $request
     * @return Json
     */
    public function requestToken2FA(Request $request)
    {
        $code = Code::where('status', $request->session()->getId())->get()->last();

        if ($code) {
            $date = new DateTime($code->created_at);
            $date->add(new DateInterval('PT' . env('CODE_2FA_EXPIRE') . 'M'));
            $now = $date->format('Y-m-d H:i:s');

            if (now() < $now) {
                throw new ReportError(Lang::get("Espera un momento por favor, el siguiente deberá ser enviado despues de " . date('H:i:s', strtotime($now))), 422);
            }
        }

        Auth2faMiddleware::generateToken($request);

        return $this->message(Lang::get('Hemos enviado el token a tu correo electronico'), 201);
    }

    /**
     * Authorize users to activate 2FA with token
     *
     * @param Request $request
     * @return Json
     */
    public function factor2faEnableOrDisable(Request $request)
    {
        $code = $this->getToken($request);

        $date = new DateTime($code->created_at);
        $date->add(new DateInterval("PT" . env('CODE_2FA_EXPIRE') . "M"));
        $expire = $date->format('Y-m-d H:i:s');

        if (!Hash::check($request->token, $code->code)) {
            return $this->message(Lang::get('El token es incorrecto.'));
        }

        if (now() > $expire) {
            return $this->message(Lang::get('El token proporciondado ha caducado'));
        }

        $user = Employee::find($request->user()->id);

        $user->m2fa = $user->m2fa ? 0 : 1;
        $user->push();

        Code::destroyToken($code->status);

        return $this->message(Lang::get($user->m2fa ? "2FA activado" : "2FA desactivado"), 201);
    }
}
