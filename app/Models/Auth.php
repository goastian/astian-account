<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use DateTime;
use DateInterval;
use LogicException;
use App\Support\CacheKeys;
use App\Models\User\UserScope;
use App\Models\Subscription\Group;
use Laravel\Passport\HasApiTokens;
use App\Repositories\Traits\Scopes;
use Elyerr\ApiResponse\Assets\Asset;
use App\Repositories\Traits\Standard;
use Illuminate\Support\Facades\Cache;
use Illuminate\Notifications\Notifiable;
use App\Notifications\Auth\ResetPassword;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Auth extends Authenticatable
{
    use HasUuids, HasApiTokens, HasFactory, Notifiable, Scopes, Asset, Standard;

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
        $user = auth()->user();

        $gsr = Cache::remember(
            CacheKeys::userAdmin($user->id),
            now()->addDays(intval(config('cache.expires', 90))),
            function () use ($user) {

                $scopes = UserScope::where('user_id', $user->id)
                    ->where(function ($query) {
                        $query->whereNull('end_date')
                            ->orWhere('end_date', '>', now());
                    })->get();

                return count($scopes) ? $scopes->pluck('gsr_id')->toArray() : [];
            }
        );

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

        if (isset($apiKey->id) && $apiKey->client->hasGrantType('personal_access')) {
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
     * @param mixed $group
     */
    public function canAccessMenu($group): bool
    {
        if (!auth()->check()) {
            return false;
        }

        if ($this->isAdmin()) {
            return true;
        }

        $groups = $this->listUserGroups();

        return count($groups) ? $groups->pluck('slug')->contains($group) : false;
    }


    /**
     * List the all active groups for user
     */
    public function listUserGroups(): mixed
    {
        if (!auth()->check()) {
            return [];
        }

        $user = auth()->user();

        $cacheKey = CacheKeys::userGroups($user->id);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        if ($user->isAdmin()) {
            $groups = Group::get()->unique()->values();

        } else {

            // Filter groups without services 
            $groupsWithoutServices = Group::whereHas(
                'users',
                function ($query) use ($user) {
                    $query->where('id', $user->id);
                }
            )->whereDoesntHave('services')->get();

            // Filter groups by Scopes
            $groupsByScopes = UserScope::with('scope.service.group')
                ->where('user_id', $user->id)
                ->where(function ($query) {
                    $query->whereNull('end_date')
                        ->orWhere('end_date', '>', now());
                })->get()
                ->map(function ($userScope) {
                    return $userScope->scope->service->group;
                })->unique()->values();

            // Join the all groups for user
            $groups = $groupsWithoutServices->concat($groupsByScopes);
        }

        Cache::put(
            $cacheKey,
            $groups,
            now()->addDays(intval(config('cache.expires', 90)))
        );

        return $groups;
    }


    /**
     * Return the groups 
     */
    public function myGroups()
    {
        $groups = $this->listUserGroups();

        if (!count($groups)) {
            return [];
        }

        return $groups->map(fn($group) => [
            'name' => $group->name,
            'slug' => $group->slug,
        ])->toArray();
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

    /**
     * Retrieve the passport providers
     * @throws \LogicException
     * @return string
     */
    public function getProviderName(): string
    {

        $providers = collect(config('auth.guards'))->where('driver', 'oauth2-passport-server')->pluck('provider')->all();

        foreach (config('auth.providers') as $provider => $config) {
            if (in_array($provider, $providers) && $config['driver'] === 'eloquent' && is_a($this, $config['model'])) {
                return $provider;
            }
        }

        throw new LogicException('Unable to determine authentication provider for this model from configuration.');
    }
}
