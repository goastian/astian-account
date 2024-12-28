<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use DateTime;
use DateInterval;
use App\Models\User\Role;
use Laravel\Passport\HasApiTokens;
use Elyerr\ApiResponse\Assets\Asset;
use App\Http\Controllers\OAuth\Scopes;
use Illuminate\Notifications\Notifiable;
use App\Notifications\Auth\ResetPassword;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
        'dial_code',
        'accept_terms'
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
        'accept_terms' => 'boolean'
    ];


    public function isAdmin()
    {
        return true; //$this->roles()->get()->contains('name', 'admin');
    }

    /**
     * verify if the user is a client
     * @return boolean
     */
    public function isClient()
    {
        return true;
        // return $this->roles()->get()->contains('name', 'client');
    }

    /**
     * 
     * @param mixed $scope
     * @return bool
     */
    public function userCan($scope)
    {
        return true;
        /*
        $roles = $this->scopes();

        if (is_array($scope)) {
            foreach ($scope as $value) {
                if ($roles->contains('id', $value)) {
                    return true;
                }
            }

            return false;
        }

        return $this->scopes()->contains('id', $scope);*/
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
