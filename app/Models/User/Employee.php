<?php

namespace App\Models\User;

use App\Events\Employee\DestroyEmployeeEvent;
use App\Models\Auth;
use App\Notifications\Client\DestroyClientNotification;
use App\Transformers\Auth\EmployeeTransformer;
use DateInterval;
use DateTime;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Employee extends Auth
{
    use SoftDeletes;

    public $table = "employees";

    //public $view = "";

    public $transformer = EmployeeTransformer::class;

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'employee_role');
    }

    /**
     * elimina clientes luego de un tiempo determinado
     */
    public static function remove_accounts()
    {
        $now = new DateTime();
        $now->sub(new DateInterval('P' . env('DESTROY_CLIENTS_AFTER', 30) . 'D'));
        $fecha = $now->format('Y-m-d H:i:s');

        $users = Employee::onlyTrashed()->where('client', 1)->where('deleted_at', "<", $fecha)->get();

        if (count($users) > 0) {

            foreach ($users as $user) {
                $user->notify(new DestroyClientNotification());
                DestroyEmployeeEvent::dispatch();
                $user->forceDelete();
            }

        }
    }

    /**
     * remove unverified accounts 
     */
    public static function remove_clients_unverified()
    {
        $now = new DateTime();
        $now->sub(new DateInterval('PT' . env('TIME_TO_VERIFY_ACCOUNT', 5) . 'M'));
        $fecha = $now->format('Y-m-d H:i:s');

        $deleted = DB::table('employees')
            ->where('client', 1)
            ->where('verified_at', null)
            ->where('created_at', "<", $fecha)
            ->delete();

        DB::table('password_resets')->where('created_at', '<', $fecha)->delete();
        
        if ($deleted) {
            DestroyEmployeeEvent::dispatch();
        }
    }
}
