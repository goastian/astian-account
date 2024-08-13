<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\GlobalController as Controller;
use App\Http\Requests\Employee\StoreRequest;
use App\Http\Requests\Employee\UpdateRequest;
use App\Models\User\Employee;
use App\Notifications\Auth\UserDisableNotification;
use App\Notifications\Client\ClientDisableNotification;
use App\Notifications\Employee\CreatedNewUser;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class UserController extends Controller
{

    public function __construct(Employee $user)
    {
        parent::__construct();
        $this->middleware('transform.request:' . $user->transformer)->only('store', 'update');
        $this->middleware('scope:account_read')->only('index', 'show');
        $this->middleware('scope:account_create')->only('store');
        $this->middleware('scope:account_update,client')->only('update');
        $this->middleware('scope:account_disable,client')->only('disable');
        $this->middleware('scope:account_enable')->only('enable');
    }

    /**
     * Display all users registered
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Employee $user)
    {
        throw_if(request()->user()->isClient(), new ReportError("The client does not have access rights to the content", 403));

        $params = $this->filter_transform($user->transformer);

        $users = $this->search($user->table, $params);

        return $this->showAll($users, $user->transformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, Employee $user)
    {

        throw_if(request()->user()->isClient(), new ReportError("The client does not have access rights to the content", 403));

        /**
         * Generate password temp for new users
         */
        $temp_password = $this->passwordTempGenerate();

        DB::transaction(function () use ($request, $user, $temp_password) {

            $user = $user->fill($request->except('password', 'role'));
            $user->verified_at = now();
            $user->password = Hash::make($temp_password);
            $user->save();

            $user->roles()->syncWithoutDetaching($request->role);

            /**
             * Send event
             */
            $this->privateChannel("StoreEmployeeEvent", "New user created");

            /**
             * Send notification
             */
            Notification::send($user, new CreatedNewUser($temp_password));
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
        $this->deny_action($id);

        $user = Employee::withTrashed()->find($id);

        return $this->showOne($user, $user->transformer);
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Employee $user)
    {
        DB::transaction(function () use ($request, $user) {

            $this->deny_action($user->id);

            throw_if(!$request->user()->isClient() and $user->isClient(), new ReportError("The client does not have access rights to the content", 403));

            $can_update = false;

            if ($this->is_diferent($user->name, $request->name)) {
                $can_update = true;
                $user->name = $request->name;
            }

            if ($this->is_diferent($user->last_name, $request->last_name)) {
                $can_update = true;
                $user->last_name = $request->last_name;
            }

            if ($this->is_diferent($user->email, $request->email)) {
                $can_update = true;
                $user->email = $request->email;
            }

            if ($this->is_diferent($user->country, $request->country)) {
                $can_update = true;
                $user->country = $request->country;
            }

            if ($this->is_diferent($user->dial_code, $request->dial_code)) {
                $can_update = true;
                $user->dial_code = $request->dial_code;
            }

            if ($this->is_diferent($user->city, $request->city)) {
                $can_update = true;
                $user->city = $request->city;
            }

            if ($this->is_diferent($user->address, $request->address)) {
                $can_update = true;
                $user->address = $request->address;
            }

            if ($this->is_diferent($user->birthday, $request->birthday)) {
                $can_update = true;
                $user->birthday = $request->birthday;
            }

            if ($this->is_diferent($user->phone, $request->phone)) {
                $can_update = true;
                $user->phone = $request->phone;
            }

            if ($can_update) {
                $user->push();
                $this->privateChannel("UpdateEmployeeEvent", "User updated");
            }

        });

        return $this->showOne($user, $user->transformer, 201);
    }

    /**
     * Disable an active user
     *
     * @return Bool
     */
    public function disable(Request $request, Employee $user)
    {
        //is not client and user id is request user id
        throw_if((!$user->isClient() && $request->user()->id == $user->id) ||
            //authenticated user is not client and user is client
            (!$request->user()->isClient() && $user->isClient()) ||
            //is client and request user id is diferent
            ($request->user()->isClient() && $request->user()->id != $user->id), new ReportError("The client does not have access rights to the content", 403));

        //Throw an exception if Two-Factor Authentication is enabled
        throw_if($request->user()->isClient() and $request->user()->m2fa, new ReportError(__("Before performing this action, you must disable Two-Factor Authentication"), 403));

        DB::transaction(function () use ($user) {

            $tokens = $user->tokens;

            $this->removeCredentials($tokens);

            $user->delete();

            $this->privateChannel("DisableEmployeeEvent", "User disabled");
        });
        /**
         * send notification a especific user
         */
        $user->isClient() ? $user->notify(new ClientDisableNotification()) : $user->notify(new UserDisableNotification());

        return $this->showOne($user, $user->transformer);
    }

    /**
     * Enable disabled users
     *
     * @return Json
     */
    public function enable($id)
    {
        //request user is not client and request user is client a client
        throw_if((!request()->user()->isClient() && request()->user()->isClient()) ||
            //request user is client
            request()->user()->isClient(), new ReportError(__("The client does not have access rights to the content"), 403));

        try {

            $user = Employee::onlyTrashed()->find($id);
            //enable user
            $user->restore();

            //send event
            $this->privateChannel("EnableEmployeeEvent", "User enabled");

            return $this->showOne($user, $user->transformer);

        } catch (Error $e) { //throw exception
            throw new ReportError("The server cannot find the requested resource", 404);
        }
    }

    /**
     * Deny action to the client user and has a different id
     *
     * @param String $id
     * @return Exception
     */
    public function deny_action($id)
    {
        throw_if(request()->user()->isClient() and request()->user()->id != $id, new ReportError("The client does not have access rights to the content", 403));
    }
}
