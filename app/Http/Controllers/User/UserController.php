<?php

namespace App\Http\Controllers\User;

use App\Events\Employee\DisableEmployeeEvent;
use App\Events\Employee\EnableEmployeeEvent;
use App\Events\Employee\StoreEmployeeEvent;
use App\Events\Employee\UpdateEmployeeEvent;
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
        $this->middleware('scope:account_register')->only('store');
        $this->middleware('scope:account_update,client')->only('update');
        $this->middleware('scope:account_disable,client')->only('disable');
        $this->middleware('scope:account_enable')->only('enable');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Employee $user)
    {
        if (request()->user()->isClient()) {
            throw new ReportError("Unauthorize", 403);
        }

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
        if (request()->user()->isClient()) {
            throw new ReportError("Unauthorize", 403);
        }

        $temp_password = $this->passwordTempGenerate();

        DB::transaction(function () use ($request, $user, $temp_password) {

            $user = $user->fill($request->except('password', 'role'));
            $user->verified_at = now();
            $user->password = Hash::make($temp_password);
            $user->save();

            $user->roles()->syncWithoutDetaching($request->role);

            StoreEmployeeEvent::dispatch();

            Notification::send($user, new CreatedNewUser($temp_password));
        });

        return $this->showOne($user, $user->transformer, 201);
    }

    /**
     * Display the specified resource.
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Employee $user)
    {
        DB::transaction(function () use ($request, $user) {

            $this->deny_action($user->id);

            if (!$request->user()->isClient() and $user->isClient()) {
                throw new ReportError("Unauthorize", 403);
            }

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
                UpdateEmployeeEvent::dispatch();
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
        if ((!$user->isClient() && $request->user()->id == $user->id) ||
            (!$request->user()->isClient() && $user->isClient()) ||
            ($request->user()->isClient() && $request->user()->id != $user->id)
        ) {
            throw new ReportError(__("Unauthorize"), 406);
        }

        if ($request->user()->isClient() and $request->user()->m2fa) {
            throw new ReportError(__("Before doing this action, you must disable Two Factor Authentication."), 403);
        }

        DB::transaction(function () use ($user) {

            $tokens = $user->tokens;

            $this->removeCredentials($tokens);

            $user->delete();

            DisableEmployeeEvent::dispatch();
        });

        $user->isClient() ? $user->notify(new ClientDisableNotification()) : $user->notify(new UserDisableNotification());

        return $this->showOne($user, $user->transformer);
    }

    /**
     * habilita un usuario deshabilitado
     *
     * @return Json
     */
    public function enable($id)
    {
        if ((!request()->user()->isClient() && request()->user()->isClient()) ||
            request()->user()->isClient()) {
            throw new ReportError(__("Unauthorize"), 403);
        }

        try {

            $user = Employee::onlyTrashed()->find($id);

            $user->restore();

            EnableEmployeeEvent::dispatch();

            return $this->showOne($user, $user->transformer);

        } catch (Error $e) {
            throw new ReportError("Error processing the request.", 404);
        }
    }

    /**
     * deny the acction if the user is a client and has diferent ID.
     *
     * @param String $id
     * @return Exception
     */
    public function deny_action($id)
    {
        if (request()->user()->isClient() and request()->user()->id != $id) {
            throw new ReportError("Unauthorize", 403);
        }
    }
}
