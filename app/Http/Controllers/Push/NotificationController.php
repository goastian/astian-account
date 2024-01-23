<?php

namespace App\Http\Controllers\Push;

use App\Events\Notification\PushNotificationEvent;
use App\Http\Controllers\GlobalController as Controller;
use App\Models\User\Employee;
use App\Models\User\Role;
use App\Notifications\Info\Alert;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:notify');
    }

    /**
     * send notification to the users by roles
     *
     * @param Request $request
     * @return Json
     */
    public function push(Request $request)
    {
        $this->validate($request, [
            'scope' => ['required'],
            'title' => ['required', 'max:50'],
            'message' => ['required', 'max:200'],
            'resource' => ['required', 'url:https'],
        ]);
        try {

            if ($request->scope == '*') {
                $users = Employee::where('id', '!=', $request->user()->id)->get();
            } else {
                $users = Role::where('name', $request->scope)->first()->users()->get();
            }

            if (count($users) > 0) {

                $data = json_decode(json_encode($request->except('scope')));

                Notification::send($users, new Alert($data));

                PushNotificationEvent::dispatch();

                return $this->message(Lang::get('Notificaciones enviadas'), 200);
            }

            return $this->message(Lang::get('No se encontro ningun usuario para enviar notificaciones'), 200);
        } catch (Error $e) {
            return $this->message(Lang::get('No se encontro ningun usuario para enviar notificaciones'), 200);
        }
    }
}
