<?php

namespace App\Http\Controllers\OAuth;

use App\Http\Controllers\GlobalController;
use App\Models\User\Employee;
use App\Models\User\Role;
use App\Notifications\Notify\Notify;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;

class PasspotConnectController extends GlobalController
{

    public function __construct()
    {
        //headers
        $scopes = request()->header('X-SCOPES');

        parent::__construct();
        $this->middleware('scope:' . $scopes)->only('check_scope');
        $this->middleware('scopes:' . $scopes)->only('check_scopes');

        if (isset($scopes)) {
            $this->middleware('client:' . $scopes)->only('check_client_credentials');
        } else {
            $this->middleware('client')->only('check_client_credentials');
        }
    }

    /**
     * gateway para verificar si un usuario esta autenticado, esta solicitud
     * lleva encabezados Authorization
     * @return null
     */
    public function check_authentication(Request $request)
    {
    }

    /**
     * gateway para verificar si almenos tiene un scope presente, esta solicitud
     * lleva encabezados Authorization, Scopes
     * @return null
     */
    public function check_scope(Request $request)
    {
    }

    /**
     * gateway para verificar si todos los scopes estan presentes, esta solicitud
     * lleva encabezados Authorization, Scopes
     * @return null
     */
    public function check_scopes(Request $request)
    {
    }

    /**
     * gateway para verificar si las credenciales del cliente son correctas, esta solicitud
     * lleva encabezados Authorization y Scopes es opcional
     * @return null
     */
    public function check_client_credentials(Request $request)
    {
    }

    /**
     * gateway para comprobar si un token puede ejecutar un scope, esta solicitud
     * lleva encabezados Authorization, Scope
     *
     * @param Request $request     *
     * @return null
     */
    public function token_can(Request $request)
    {
        $scope = $request->header('X-SCOPE');

        $status = request()->user()->tokenCan($scope);

        return $status ? response(null, 200) : response(null, 403);
    }

    /**
     * gateway que permite obtener los datos del usuario autenticado
     *
     * @param Request $request
     */
    public function auth(Request $request)
    {
        return $this->authenticated_user();
    }

    /**
     * getway para el envio de notificaciones
     *
     * @param Request $request
     */

    public function send_notification(Request $request)
    {
        $X_HEADER = $request->header('X-VERIFY-NOTIFICATION');

        throw_unless($X_HEADER == env('VERIFY_NOTIFICATION'), new ReportError("Unsupported header", 422));

        $this->validate($request, [
            'via' => ['required', 'array', Rule::in(['database', 'mail'])],
            'subject' => ['required', 'max:50'],
            'message' => ['required', 'max:500'],
            'resource' => ['nullable', 'url:https'],
            'users' => ['required'],
        ]);

        $data = json_decode(json_encode($request->all()));

        $notifiable = null;

        if ($data->users == '*') {
            $notifiable = Employee::where('id', '!=', $request->user()->id)->get();

        } elseif ($this->is_email($data->users)) {
            $notifiable = Employee::where('email', $data->users)->first();
            if (!$notifiable) {
                throw new ReportError("the email does not exists", 422);
            }
        } elseif (Role::get()->contains('name', $data->users)) {
            $notifiable = Role::where('name', $data->users)->first()->users()->get();
        } else {
            throw new ReportError("Unsupported data", 422);
        }

        Notification::send($notifiable, new Notify($data));

        return $this->message(__('notification sent successfully'), 201);
    }

    /**
     * verify the email key
     *
     * @param string $value
     * @return bool
     */
    public function is_email($value)
    {
        $valid_email = filter_var($value, FILTER_VALIDATE_EMAIL);

        if ($valid_email) {
            list($usuario, $dominio) = explode('@', $value);

            if (strpos($dominio, '.') !== false) {
                list($dominio, $extension) = explode('.', $dominio);
                if (strlen($extension) >= 2) {
                    return true;
                }
            }
        }
        return false;
    }
}
