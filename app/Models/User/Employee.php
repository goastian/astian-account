<?php

namespace App\Models\User;

use DateTime;
use DateInterval;
use App\Models\Auth;
use App\Events\Employee\DestroyEmployeeEvent;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Transformers\Auth\EmployeeTransformer;
use App\Notifications\Client\DestroyClientNotification;

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
}
