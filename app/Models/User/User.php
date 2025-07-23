<?php
namespace App\Models\User;

use App\Models\Auth;
use App\Models\User\Partner;
use Illuminate\Http\Request;
use App\Models\Setting\Terminal;
use Illuminate\Support\Facades\Hash;
use App\Transformers\User\UserTransformer;
use App\Models\Subscription\PaymentProvider;
use Illuminate\Database\Eloquent\SoftDeletes;

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
     * Retrieve a middle name
     * @return string
     */
    public function getMiddleName()
    {
        $data = explode(" ", $this->name);
        unset($data[0]);

        return implode($data);
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

    public function paymentProviders()
    {
        return $this->hasMany(PaymentProvider::class, 'user_id');
    }
}
