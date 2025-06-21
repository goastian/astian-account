<?php
namespace App\Repositories;

use DateTime;
use Exception;
use DateInterval;
use ErrorException;
use App\Models\User\User;
use App\Models\User\Partner;
use Illuminate\Http\Request;
use App\Models\User\UserScope;
use App\Models\Subscription\Group;
use Illuminate\Support\Facades\DB;
use Elyerr\ApiResponse\Assets\Asset;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\Contracts;
use App\Transformers\User\AuthTransformer;
use App\Notifications\User\UserUpdatedEmail;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Illuminate\Support\Facades\Notification;
use App\Notifications\User\UserCreatedAccount;
use App\Notifications\User\UserDisableAccount;
use Elyerr\ApiResponse\Exceptions\ReportError;
use App\Transformers\User\UserGroupTransformer;
use App\Notifications\User\UserReactivateAccount;
use App\Notifications\Member\MemberCreatedAccount;
use App\Repositories\OAuth\Server\Grant\OAuthSessionTokenRepository;

class UserRepository implements Contracts
{

    use JsonResponser, Asset;

    /**
     * User model
     * @var User
     */
    public $model;

    /**
     * User scope model
     * @var UserScope
     */
    public $userScope;

    /**
     * Group model
     * @var Group
     */
    public $group;

    /**
     * Partner repository
     * @var PartnerRepository
     */
    public $partnerRepository;

    /**
     * Oauth token repository
     * @var OAuthSessionTokenRepository
     */
    public $oauthSessionTokenRepository;

    /**
     * Construct
     * @param \App\Models\User\User $user
     * @param \App\Models\User\UserScope $userScope
     * @param \App\Models\Subscription\Group $group
     * @param \App\Repositories\PartnerRepository $partnerRepository
     * @param \App\Repositories\OAuth\Server\Grant\OAuthSessionTokenRepository $oAuthSessionTokenRepository
     */
    public function __construct(
        User $user,
        UserScope $userScope,
        Group $group,
        PartnerRepository $partnerRepository,
        OAuthSessionTokenRepository $oAuthSessionTokenRepository
    ) {
        $this->model = $user;
        $this->userScope = $userScope;
        $this->group = $group;
        $this->partnerRepository = $partnerRepository;
        $this->oauthSessionTokenRepository = $oAuthSessionTokenRepository;
    }

    /**
     * Search the user resources
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        // Retrieve params of request
        $params = $this->filter_transform($this->model->transformer);

        // Prepare query
        $data = $this->model->query();

        // Add users trashed
        $data = $data->withTrashed()->orderByDesc('created_at');

        // Search by params
        $data = $this->searchByBuilder($data, $params);

        return $this->showAllByBuilder($data, $this->model->transformer);
    }

    /**
     * Create new resource of the user
     * @param array $data
     * @return JsonResponser
     */
    public function create(array $data)
    {
        $temp_password = $this->passwordTempGenerate();

        $user = $this->model->create([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($temp_password),
            'country' => $data['country'] ?? null,
            'city' => $data['city'] ?? null,
            'address' => $data['address'] ?? null,
            'dial_code' => $data['dial_code'] ?? null,
            'phone' => $data['phone'] ?? null,
            'birthday' => $data['birthday'] ?? null,
            'verified_at' => $data['verify_email'] ? now() : false,
            'accept_terms' => $data['accept_terms'] ?? true,
            'accept_cookies' => $data['accept_cookies'] ?? true,
            'partner_id' => $data['partner_id'] ?? null,
        ]);

        Notification::send(
            $user,
            new UserCreatedAccount($temp_password)
        );

        return $this->showOne($user, $user->transformer, 201);
    }

    /**
     * Find the user
     * @param string $id
     * @return User
     */
    public function find(string $id)
    {
        return $this->model->withTrashed()->with([
            'partner',
            'userScopes',
            'groups'
        ])->find($id);
    }

    /**
     * Show details of the user
     * @param string $user_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function details(string $user_id)
    {
        $user = $this->find($user_id);

        return $this->showOne($user, $user->transformer);
    }

    /**
     * Update specific resource
     * @param string $id
     * @param array $data
     * @return JsonResponser
     */
    public function update(string $id, array $data)
    {
        $model = $this->model->find($id);
        $can_update = false;
        $updated_email = false;

        if (!empty($data['name']) && strtolower($model->name) != strtolower($data['name'])) {
            $can_update = true;
            $model->name = $data['name'];
        }

        if (!empty($model->verified_at) && strtolower($data['verify_email'])) {
            $can_update = true;
            $model->verified_at = now();
        }

        if (!empty($data['last_name']) && $model->last_name != $data['last_name']) {
            $can_update = true;
            $model->last_name = $data['last_name'];
        }

        if (!empty($data['email']) && $model->email != $data['email']) {
            $updated_email = true;
            $can_update = true;
            $model->email = $data['email'];
        }

        if (!empty($data['country']) && $model->country != $data['country']) {
            $can_update = true;
            $model->country = $data['country'];
        }

        if (!empty($data['dial_code']) && $model->dial_code != $data['dial_code']) {
            $can_update = true;
            $model->dial_code = $data['dial_code'];
        }

        if (!empty($data['city']) && $model->city != $data['city']) {
            $can_update = true;
            $model->city = $data['city'];
        }

        if (!empty($data['address']) && $model->address != $data['address']) {
            $can_update = true;
            $model->address = $data['address'];
        }

        if (!empty($data['birthday']) && $model->birthday != $data['birthday']) {
            $can_update = true;
            $model->birthday = $data['birthday'];
        }

        if (!empty($data['phone']) && $model->phone != $data['phone']) {
            $can_update = true;
            $model->phone = $data['phone'];
        }

        if ($can_update) {
            $model->push();
        }

        /**
         * Update the commission rate
         */
        if (!empty($model->partner_id) && !$data['commission_rate'] != $model->partner->commission_rate) {
            $this->partnerRepository->updateCommissionRate(
                $model->partner,
                $data['commission_rate']
            );
        }

        if ($updated_email) {
            Notification::send($model, new UserUpdatedEmail());
        }

        return $this->showOne($model, $this->model->transformer, 200);
    }

    /**
     * Delete specific resource
     * @param string $id 
     * @return void
     */
    public function delete(string $id)
    {
        // to complete
    }

    /**
     * Disable users  and destroy all sessions
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function disable(string $id)
    {
        $model = $this->model->find($id);

        $tokens = $model->tokens;

        foreach ($tokens as $token) {
            $this->oauthSessionTokenRepository->destroyTokenSession($token->oauthSessionToken->session_id);
        }

        $model->delete();


        Notification::send($model, new UserDisableAccount());


        return $this->showOne($model, $this->model->transformer);
    }

    /**
     * Enable disabled users
     * @param string $id
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function enable(string $id)
    {
        try {

            $user = $this->model->onlyTrashed()->find($id);

            $user->restore();

            Notification::send($user, new UserReactivateAccount());

            return $this->showOne($user, $user->transformer);

        } catch (Exception $e) {
            throw new ReportError(__("The server cannot find the requested resource"), 404);
        }
    }

    /**
     * Search the all scopes available for the user
     * @param string $user_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function searchScopesForUser(string $user_id)
    {
        $userScopes = $this->userScope->query();

        $userScopes->where('user_id', $user_id)
            ->where(function ($query) {
                $query->where('end_date', '>', now())
                    ->orWhereNull('end_date');
            })
            ->whereHas('scope', function ($query) {
                $query->where('active', '!=', false);
            })
            ->with(['scope.service.group', 'scope.role']);

        return $this->showAllByBuilder($userScopes, $this->userScope->transformer);
    }

    /**
     * Retrieve the all groups for the user
     * @param string $user_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function searchGroupsForUser(string $user_id)
    {
        $groups = $this->group->query();

        $groups->whereHas('users', function ($query) use ($user_id) {
            $query->where('id', $user_id);
        });

        return $this->showAllByBuilder($groups, UserGroupTransformer::class);
    }

    /**
     * Assign scope for the user
     * @param string $user_id
     * @param array $data
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assignScopeForUser(string $user_id, array $data)
    {
        DB::transaction(function () use ($user_id, $data) {

            foreach ($data['scopes'] as $id) {

                $userScope = $this->userScope->query();

                $us = $userScope->whereNull('package_id')
                    ->where(function ($query) {
                        $query->whereNull('end_date')
                            ->orWhere('end_date', '>', now());
                    })
                    ->where('scope_id', $id)
                    ->where('user_id', $user_id)
                    ->updateOrCreate([
                        'scope_id' => $id,
                        'user_id' => $user_id,
                        'end_date' => $data['end_date'] ?? null
                    ]);

                //sync groups by scopes
                $this->model->find($user_id)->groups()->sync($us->scope->service->group->id);
            }
        });

        return $this->message(__("Scopes have been assigned successfully"), 201);
    }

    /**
     * Assign groups to the user
     * @param string $user_id
     * @param array $data
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assignGroupForUser(string $user_id, array $data)
    {
        $user = $this->model->find($user_id);

        $user->groups()->syncWithoutDetaching($data['groups']);

        return $this->message(__('Groups assigned successfully'), 201);
    }

    /**
     * Revoke scopes to the user
     * @param string $user_id
     * @param string $scope_id
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function revokeScopeForUser(string $user_id, string $id)
    {
        $scope = $this->userScope
            ->where('id', $id) // by id
            ->where('user_id', $user_id) //by user id
            ->first();

        // package is null
        if (!empty($scope->package_id)) {
            throw new ReportError(
                __('This scope cannot be deleted because it is associated with a paid plan. Please contact support if you believe this is an error.'),
                400
            );
        }

        if ($user_id == auth()->user()->id || $user_id != $scope->user_id) {
            throw new ReportError(
                __('You cannot remove your own permissions. Please contact an administrator if you require changes to your access.'),
                400
            );
        }

        // Set expiration date for the scopes
        if (empty($scope->end_date) || $scope->end_date >= now()) {
            $scope->end_date = now();
            $scope->push();
        }

        return $this->message(__("Scopes have been revoked successfully"), 200);
    }

    /**
     * Revoke group for the user
     * @param mixed $user_id
     * @param string $group_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function revokeGroupForUser($user_id, string $group_id)
    {
        $model = $this->model->find($user_id);

        $model->groups()->detach($group_id);

        return $this->message(__('Groups revoked successfully'), 200);
    }

    /**
     * Show the history the all scopes assigner for the user
     * @param string $user_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function searchScopeHistoryForUser(string $user_id)
    {
        $scopes = $this->userScope->query();
        $scopes->where('user_id', $user_id);

        return $this->showAllByBuilder($scopes, $this->userScope->transformer);
    }

    /**
     * Update personal user information
     * @param array $data
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function updatePersonalInformation(array $data)
    {
        $user = $this->model->find(auth()->user()->id);

        if (!empty($data['name'])) {
            $user->name = $data['name'];
        }

        if (!empty($data['last_name'])) {
            $user->last_name = $data['last_name'];
        }

        if (!empty($data['email'])) {
            $user->email = $data['email'];
        }

        if (!empty($data['country'])) {
            $user->country = $data['country'];
        }

        if (!empty($data['city'])) {
            $user->city = $data['city'];
        }

        if (!empty($data['address'])) {
            $user->address = $data['address'];
        }

        if (!empty($data['dial_code'])) {
            $user->dial_code = $data['dial_code'];
        }

        if (!empty($data['phone'])) {
            $user->phone = $data['phone'];
        }

        if (!empty($data['birthday'])) {
            $user->birthday = $data['birthday'];
        }

        $user->push();

        return $this->showOne($user, AuthTransformer::class);
    }

    /**
     * Update personal password for the user
     * @param array $data
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function updatePersonalPassword(array $data)
    {
        $user = auth()->user();

        $user->password = Hash::make($data['password']);
        $user->push();

        return $this->message(__("password changed successfully"), 200);
    }

    /**
     * Register new users (customers)
     * @param array $data
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerCustomer(array $data)
    {
        $group = Group::where('slug', 'member')->first();

        if (empty($group)) {
            return back()->with('error', __('The registration could not be completed successfully. Our team has been notified of the issue and is working to resolve it. We appreciate your patience and encourage you to try again later'));
        }

        DB::transaction(function () use ($data, $group) {

            $user = $this->model->create([
                'name' => $data['name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'birthday' => $data['birthday'],
                'accept_terms' => $data['accept_terms'],
                'accept_cookies' => $data['accept_cookies'],
                'referral_code' => $data['referral_code'] ?? true,
            ]);

            if ($data['referral_code']) {

                $partner = Partner::where(
                    'code',
                    $data['referral_code']
                )->first();

                if (!empty($partner)) {
                    $user->partner_id = $partner->id;
                }
            }

            $user->groups()->attach($group->id);

            $user->notify(new MemberCreatedAccount());
        });

        return redirect()->route('login')->with('status', __('Your account has been registered successfully. A verification email has been sent to your inbox.'));
    }

    /**
     * Verify user accounts
     * @param array $data
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function verifyUserAccount(array $data)
    {
        try {

            $data = DB::table('password_resets')->where([
                'token' => $data['token'],
                'email' => $data['email'],
            ])->first();

            $now = new DateTime($data->created_at);
            $now->add(new DateInterval("PT" . config("system.verify_account_time", 5) . "M"));
            $date = $now->format("Y-m-d H:i:s");

            DB::table('password_resets')->where('email', '=', $data['email'])->delete();

            $user = $this->model->where('email', $data['email'])->first();

            if (date('Y-m-d H:i:s', strtotime(now())) > $date) {
                $user->forceDelete();
                throw new ReportError(__("Time's up to activate the account"), 403);
            }

            $user->verified_at = now();
            $user->save();

            $token = uniqid();

            return redirect()->route('users.verified.account')->with(
                [
                    'status' => __('Your account has been activated.'),
                    'token' => $token
                ]
            );

        } catch (ErrorException $e) {
            if (auth()->check()) {
                auth()->logout();
            }

            return redirect()->route('login');
        }
    }

}
