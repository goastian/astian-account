<?php

namespace App\Http\Controllers\User;

use Error;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
use App\Http\Controllers\GlobalController as Controller;

class UserController extends Controller
{

    public function __construct(User $user)
    {
        parent::__construct();
        $this->middleware('scope:administrator_user_full,administrator_user_view')->only('index');
        $this->middleware('scope:administrator_user_full,administrator_user_show')->only('show');
        $this->middleware('scope:administrator_user_full,administrator_user_create')->only('store');
        $this->middleware('scope:administrator_user_full,administrator_user_update')->only('update');
        $this->middleware('scope:administrator_user_full,administrator_user_disable')->only('disable');
        $this->middleware('scope:administrator_user_full,administrator_user_enable')->only('enable');
    }

    /**
     * Summary of index
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request, User $user)
    {
        $this->checkMethod('get');

        $params = $this->filter_transform($user->transformer);

        $data = $user->query();
        $data = $this->searchByBuilder($data, $params);
        $data = $this->orderByBuilder($data, $user->transformer);

        return $this->showAllByBuilder($data, $user->transformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, User $user)
    {
        $this->checkMethod('post');

        $this->checkContentType($this->getPostHeader());

        DB::transaction(function () use ($request, $user) {

            $temp_password = $this->passwordTempGenerate();

            $user = $user->fill($request->except('password', 'role'));
            $user->verified_at = now();
            $user->password = Hash::make($temp_password);
            $user->save();

            $user->groups()->attach($request->groups);

            /**
             * Send event
             */
            $this->privateChannel("StoreUserEvent", "New user created");

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
        $this->checkMethod('get');

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
        $this->checkMethod('put');

        $this->checkContentType($this->getUpdateHeader());

        DB::transaction(function () use ($request, $user) {
            $updated_password = false;
            $updated_email = false;
            $can_update = false;

            if ($this->is_different($user->name, $request->name)) {
                $can_update = true;
                $user->name = $request->name;
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

            if ($can_update) {
                $user->push();
                $this->privateChannel("UpdateUserEvent", "User updated");
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
        $this->checkMethod('delete');

        $this->checkContentType(null);

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
        $this->checkMethod('get');

        $this->checkContentType(null);

        try {

            $user = User::onlyTrashed()->find($id);

            $user->restore();

            Notification::send($user, new UserReactivateAccount());

            $this->privateChannel("EnableUserEvent", "User enabled");

            return $this->showOne($user, $user->transformer);

        } catch (Error $e) {
            throw new ReportError(__("The server cannot find the requested resource"), 404);
        }
    }
}
