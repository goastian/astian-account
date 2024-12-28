<?php

namespace App\Models\User;

use DateTime;
use DateInterval;
use App\Models\Auth;
use App\Models\User\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Elyerr\Echo\Client\PHP\Socket\Socket;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Transformers\Auth\EmployeeTransformer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Notifications\Client\DestroyClientNotification;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Auth
{
    use SoftDeletes, Socket;

    /**
     * Table name
     * @var String
     */
    public $table = "users";

    //public $view = "";
    /**
     * Class to transform data
     *
     * @var EmployeeTransformer
     */
    public $transformer = EmployeeTransformer::class;

    /**
     * @return BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }


    /**
     * Retrieve the all group for current user
     *
     * @return
     */
    public function getGroups()
    {
        $group_ids = $this->roles()->get()->pluck('group_id');

        $groups = Group::whereIn('id', $group_ids)->get()->pluck('name');

        return $groups;
    }

    /**
     * Remove client accounts after 30 days.
     *
     * @return void
     */
    public function remove_accounts()
    {
        $now = new DateTime();
        $now->sub(new DateInterval('P' . env('DESTROY_CLIENTS_AFTER', 30) . 'D'));
        $fecha = $now->format('Y-m-d H:i:s');

        $users = User::onlyTrashed()->where('deleted_at', "<", $fecha)->get();

        if (count($users) > 0) {

            foreach ($users as $user) {
                if ($user->isClient()) {
                    $user->notify(new DestroyClientNotification());

                    $user->forceDelete();

                    $this->privateChannel('DestroyEmployeeEvent', 'Account deleted');
                }
            }
        }
    }

    /**
     * Remove unverified accounts
     *
     * @return void
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

        if ($deleted) {
            $this->privateChannel('DestroyEmployeeEvent', 'Account deleted');
        }
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

        $user = User::where('email', $request->email)->first();

        if ($user && $user->m2fa && Hash::check($request->password, $user->password)) {
            return true;
        }

        return false;
    }

    /**
     * Accept terms privacy
     *
     * @return void
     */
    public function acceptTerms()
    {
        $this->accept_terms = true;
        $this->push();
    }
}
