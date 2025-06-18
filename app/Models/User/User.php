<?php
namespace App\Models\User;

use DateTime;
use DateInterval;
use App\Models\Auth;
use App\Models\User\Partner;
use Illuminate\Http\Request;
use App\Models\Setting\Terminal;
use App\Models\Subscription\Group;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Transformers\User\UserTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\Member\MemberDestroyAccount;

class User extends Auth
{
    use SoftDeletes;


    /**
     * Transformer
     * @var 
     */
    public $transformer = UserTransformer::class;

    protected $fillable = [
        "name",
        "last_name",
        "email",
        'password',
        'country',
        'city',
        'address',
        'dial_code',
        'phone',
        'birthday',
        'verified_at',
        'm2fa',
        'totp',
        'stripe_customer_id',
        'partner_id',
        'accept_cookies',
        'accept_terms',
        'last_connected',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verified_at' => 'datetime',
        'accept_cookies' => 'boolean',
        'accept_terms' => 'boolean',
        'last_connected' => 'datetime'
    ];

    /**
     * Destroy users
     * @return void
     */
    public function destroyAccounts()
    {
        $now = new DateTime();
        $now->sub(new DateInterval('P' . config('system.destroy_user_after', 30) . 'D'));
        $date = $now->format('Y-m-d H:i:s');

        $users = User::onlyTrashed()
            ->whereHas('groups', function ($query) {
                $query->where('slug', 'member');
            })
            ->where('deleted_at', '<', $date)
            ->get();

        if (count($users) > 0) {
            foreach ($users as $user) {
                $user->notify(new MemberDestroyAccount());
                $user->forceDelete();
            }
        }
    }

    /**
     * Remove unverified accounts
     *
     * @return void
     */
    public function destroyUnverifiedMembers()
    {
        $now = new DateTime();
        $now->sub(new DateInterval('PT' . config('system.verify_account_time', 5) . 'M'));
        $date = $now->format('Y-m-d H:i:s');

        $users = User::whereHas('groups', function ($query) {
            $query->where('slug', 'member');
        })
            ->whereNull('verified_at')
            ->where('created_at', '<', $date)
            ->get();

        foreach ($users as $user) {
            $user->forceDelete();
        }

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
        $groups = $this->groups()->get();

        if ($this->isAdmin()) {
            $groups = Group::all();
        }

        return $groups->map(fn($group) => [
            'name' => $group->name,
            'slug' => $group->slug,
        ])->toArray();
    }

    /**
     * Terminals
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function terminals()
    {
        return $this->hasMany(Terminal::class);
    }

    /**
     * Has one
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function partner()
    {
        return $this->hasOne(Partner::class);
    }
}
