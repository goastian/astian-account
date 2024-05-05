<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Http\Controllers\OAuth\Scopes;
use App\Models\User\Role;
use App\Notifications\Auth\ResetPassword;
use DateInterval;
use DateTime;
use Elyerr\ApiResponse\Assets\Asset;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Auth extends Authenticatable
{
    use HasUuids, HasApiTokens, HasFactory, Notifiable, Scopes, Asset;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'country',
        'city',
        'address',
        'phone',
        'birthday',
        'client',
        'm2fa',
        'totp',
        'dial_code'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verified_at' => 'datetime',
    ];

    /**
     * verifica si contiene el role de administrador
     */
    public function isAdmin()
    {
        return $this->roles()->get()->contains('name', 'admin');
    }

    /**
     * verify if the user is a client
     * @return boolean
     */
    public function isClient()
    {
        return $this->roles()->get()->contains('name', 'client');
    }

    /**
     * Checking if the user has an access for any scope
     *
     * @return Boolean
     */
    public function userCan($scope)
    {
        $roles = $this->scopes();
        
        if (is_array($scope)) {
            foreach ($scope as $value) {
                if ($roles->contains('id', $value)) {
                    return true;
                }
            }

            return false;
        }

       return $this->scopes()->contains('id', $scope);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * get scope for the client
     *
     * @return Role
     */
    public function addClientScope()
    {
        $scope = Role::where('name', 'client')->first();
        return $scope;
    }

    /**
     * Setting the date for registered users
     *
     * @param Int $years
     * @return date
     */
    public static function setBirthday($years = 13)
    {
        $date = new DateTime();
        $date->sub(new DateInterval('P' . $years . 'Y'));
        return $date->format('Y-m-d');
    }
}
