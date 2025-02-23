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
use App\Transformers\Subscription\GroupTransformer;
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
     * Destroy users
     * @return void
     */
    public function remove_accounts()
    {
        $now = new DateTime();
        $now->sub(new DateInterval('P' . settingItem('destroy_user_after', 30) . 'D'));
        $date = $now->format('Y-m-d H:i:s');

        $users = User::onlyTrashed()->where('deleted_at', "<", $date)->get();

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
        $now->sub(new DateInterval('PT' . settingItem('verify_account_time', 5) . 'M'));
        $date = $now->format('Y-m-d H:i:s');

        $deleted = DB::table('users')
            ->where('verified_at', null)
            ->where('created_at', "<", $date)
            ->delete();

        DB::table('password_resets')->where('created_at', '<', $date)->delete();


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
     * Return the groups 
     */
    public function myGroups()
    {
        $groups = fractal($this->groups()->get(), GroupTransformer::class);
        return json_decode(json_encode($groups))->data;
    }
}
