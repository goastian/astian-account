<?php

namespace App\Http\Controllers\Web\Admin\User;

use Error;
use Inertia\Inertia;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\WebController;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Notifications\User\UserUpdatedEmail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\User\UserCreatedAccount;
use App\Notifications\User\UserDisableAccount;
use Elyerr\ApiResponse\Exceptions\ReportError;
use App\Notifications\User\UserUpdatedPassword;
use App\Notifications\User\UserReactivateAccount;
use App\Notifications\Member\MemberCreatedAccount;

class UserController extends WebController
{

    public function __construct(User $user)
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator_user_full,administrator_user_view')->only('index');
        $this->middleware('userCanAny:administrator_user_full,administrator_user_show')->only('show');
        $this->middleware('userCanAny:administrator_user_full,administrator_user_create')->only('store');
        $this->middleware('userCanAny:administrator_user_full,administrator_user_update')->only('update');
        $this->middleware('userCanAny:administrator_user_full,administrator_user_disable')->only('disable');
        $this->middleware('userCanAny:administrator_user_full,administrator_user_enable')->only('enable');

        $this->middleware('wants.json')->only('show');
    }

    /**
     * Summary of index
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request, User $user)
    {
        // Retrieve params of request
        $params = $this->filter_transform($user->transformer);

        // Prepare query
        $data = $user->query();

        // Add users trashed
        $data = $data->withTrashed();

        // Search by params
        $data = $this->searchByBuilder($data, $params);

        // Order by
        $data = $this->orderByBuilder($data, $user->transformer);
        
        if (request()->wantsJson()) {
            return $this->showAllByBuilder($data, $user->transformer);
        }

        return Inertia::render("Admin/Users/Index", [
            "route" => route('admin.users.index')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, User $user)
    {
        DB::transaction(function () use ($request, $user) {

            $temp_password = $this->passwordTempGenerate();

            $user = $user->fill($request->except('password', 'role'));
            $user->verified_at = now();
            $user->password = Hash::make($temp_password);
            if ($request->verify_email) {
                $user->verified_at = now();
            }
            $user->save();

            Notification::send($user, new UserCreatedAccount($temp_password));

        });

        return $this->showOne($user, $user->transformer, 201);
    }

    /**
     * Display the user
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::withTrashed()->find($id);

        return $this->showOne($user, $user->transformer);
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, User $user)
    {
        DB::transaction(function () use ($request, $user) {
            $updated_password = false;
            $updated_email = false;
            $can_update = false;

            if ($this->is_different($user->name, $request->name)) {
                $can_update = true;
                $user->name = $request->name;
            }

            if (empty($user->verified_at) && $request->verify_email) {
                $can_update = true;
                $user->verified_at = now();
            }

            if ($this->is_different($user->last_name, $request->last_name)) {
                $can_update = true;
                $user->last_name = $request->last_name;
            }

            if ($this->is_different($user->email, $request->email)) {
                $updated_email = true;
                $can_update = true;
                $user->email = $request->email;
            }

            if ($this->is_different($user->country, $request->country)) {
                $can_update = true;
                $user->country = $request->country;
            }

            if ($this->is_different($user->dial_code, $request->dial_code)) {
                $can_update = true;
                $user->dial_code = $request->dial_code;
            }

            if ($this->is_different($user->city, $request->city)) {
                $can_update = true;
                $user->city = $request->city;
            }

            if ($this->is_different($user->address, $request->address)) {
                $can_update = true;
                $user->address = $request->address;
            }

            if ($this->is_different($user->birthday, $request->birthday)) {
                $can_update = true;
                $user->birthday = $request->birthday;
            }

            if ($this->is_different($user->phone, $request->phone)) {
                $can_update = true;
                $user->phone = $request->phone;
            }

            if ($request->password && !Hash::check($request->password, $user->password)) {
                $updated_password = true;
                $can_update = true;
                $user->password = Hash::make($request->password);
            }

            if ($request->has('commission_rate') && !empty($user->partner) && $request->commission_rate != $user->partner->commission_rate) {
                $user->partner->updateCommissionRate($request->commission_rate);
            }

            if ($can_update) {
                $user->push();

            }

            if ($updated_password) {
                Notification::send($user, new UserUpdatedPassword());
            }

            if ($updated_email) {
                Notification::send($user, new UserUpdatedEmail());
            }
        });

        return $this->showOne($user, $user->transformer, 200);
    }

    /**
     * Disable accounts
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function disable(Request $request, User $user)
    {
        DB::transaction(function () use ($user) {

            $tokens = $user->tokens;

            $this->removeCredentials($tokens);

            $user->delete();

            Notification::send($user, new UserDisableAccount());

            $this->privateChannel("DisableUserEvent", "User disabled");
        });

        return $this->showOne($user, $user->transformer);
    }

    /**
     * Enable accounts
     * @param mixed $id
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function enable($id)
    {
        try {

            $user = User::onlyTrashed()->find($id);

            $user->restore();

            Notification::send($user, new UserReactivateAccount());

            return $this->showOne($user, $user->transformer);

        } catch (Error $e) {
            throw new ReportError(__("The server cannot find the requested resource"), 404);
        }
    }
}
