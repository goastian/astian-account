<?php
namespace App\Models\User;

use DateTime;
use DateInterval;
use App\Models\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Transformers\User\UserTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\Client\DestroyClientNotification;

class User extends Auth
{
    use SoftDeletes;

    /**
     * Transformer
     * @var 
     */
    public $transformer = UserTransformer::class;

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
     * Relationship with scopes
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userScopes()
    {
        return $this->hasMany(UserScope::class);
    }
}
