<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use DateTime;
use DateInterval;  
use App\Models\User\UserScope;
use App\Models\Subscription\Group;
use Laravel\Passport\HasApiTokens;
use App\Repositories\Traits\Scopes;
use Elyerr\ApiResponse\Assets\Asset;
use App\Repositories\Traits\Standard;
use Illuminate\Notifications\Notifiable;
use App\Notifications\Auth\ResetPassword;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Auth extends Authenticatable
{
    use HasUuids, HasApiTokens, HasFactory, Notifiable, Scopes, Asset, Standard, Scopes;

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


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Check the admin user
     * @return bool
     */
    public function isAdmin()
    {
        $gsr = auth()->user()->userScopes()->get()->pluck('gsr_id')->toArray();
        return in_array($this->adminScopeName(), $gsr);
    }

    /**
     * Name of admin scope
     * @return string
     */
    public function adminScopeName()
    {
        return "administrator:admin:full";
    }

    /**
     * Determine if the current API token has a given scope
     *
     * @param  string  $scope
     * @return bool
     */
    public function tokenCan($scope)
    {
        $apiKey = $this->accessToken;

        if (isset($apiKey->id)) {
            if (in_array($scope, $apiKey->scopes)) {
                return true;
            }
            return false;
        }

        if (auth()->check()) {
            $userScopes = $this->scopes();
            if (!empty($userScopes) && in_array($scope, $userScopes->pluck('id')->toArray())) {
                return true;
            }
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
     * Setting the time to create account
     * @param mixed $years
     * @return string
     */
    public static function setBirthday($years = 13)
    {
        $date = new DateTime();
        $date->sub(new DateInterval('P' . $years . 'Y'));
        return $date->format('Y-m-d');
    }

    /**
     * Groups
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Group, Auth>
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    /**
     * Check if the user has a group
     * @return bool
     */
    public function hasGroup($group)
    {
        return $this->groups()->get()->pluck('slug')->contains($group);
    }


    /**
     * Retrieve metadata of the model
     * @param array $transformer
     */
    public function meta($transformer = null)
    {
        $data = fractal($this, $transformer ?? $this->transformer)->toArray()['data'];
        unset($data['links']);
        return $data;
    }

    /**
     * Updated the las connection
     * @return void
     */
    public function lastConnected()
    {
        $this->last_connected = now();
        $this->push();
    }
}
