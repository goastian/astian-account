<?php

namespace App\Models\User;

use App\Models\Auth;
use App\Notifications\Client\DestroyClientNotification;
use App\Transformers\Auth\EmployeeTransformer;
use DateInterval;
use DateTime;
use Elyerr\Echo\Client\PHP\Socket\Socket;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Employee extends Auth
{
    use SoftDeletes, Socket;

    public $table = "users";

    //public $view = "";

    public $transformer = EmployeeTransformer::class;

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    /**
     * elimina clientes luego de un tiempo determinado
     */
    public function remove_accounts()
    {
        $now = new DateTime();
        $now->sub(new DateInterval('P' . env('DESTROY_CLIENTS_AFTER', 30) . 'D'));
        $fecha = $now->format('Y-m-d H:i:s');

        $users = Employee::onlyTrashed()->where('deleted_at', "<", $fecha)->get();

        if (count($users) > 0) {

            foreach ($users as $user) {
                if ($user->isClient()) {
                    $user->notify(new DestroyClientNotification());

                    // $this->privateChannel('DestroyEmployeeEvent', 'Account deleted');

                    $user->forceDelete();
                }
            }

        }
    }

    /**
     * remove unverified accounts
     */
    public function remove_clients_unverified()
    {
        $now = new DateTime();
        $now->sub(new DateInterval('PT' . env('TIME_TO_VERIFY_ACCOUNT', 5) . 'M'));
        $fecha = $now->format('Y-m-d H:i:s');

        $deleted = DB::table('users')
            ->where('verified_at', null)
            ->where('created_at', "<", $fecha)
            ->delete();

        DB::table('password_resets')->where('created_at', '<', $fecha)->delete();

        /* if ($deleted) {
    $this->privateChannel('DestroyEmployeeEvent', 'Account deleted');
    }*/
    }

    /**
     * Verify the correct user and check if they have activated 2FA.
     *
     * @param Request $request
     */
    public static function validate(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = Employee::where('email', $request->email)->first();

        if ($user && $user->m2fa && Hash::check($request->password, $user->password)) {
            return true;
        }

        return false;
    }
}
