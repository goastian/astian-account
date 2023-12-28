<?php

namespace App\Http\Controllers\User;

use App\Events\Employee\DestroyEmployeeEvent;
use App\Events\Employee\DisableEmployeeEvent;
use App\Events\Employee\EnableEmployeeEvent;
use App\Events\Employee\StoreEmployeeEvent;
use App\Events\Employee\UpdateEmployeeEvent;
use App\Http\Controllers\GlobalController as Controller;
use App\Http\Requests\Employee\StoreRequest;
use App\Http\Requests\Employee\UpdateRequest;
use App\Models\User\Employee;
use App\Notifications\Client\DestroyClientNotification;
use App\Notifications\Employee\CreatedNewUser;
use DateInterval;
use DateTime;
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
        $this->middleware('scope:account,account_read')->only('index', 'show');
        $this->middleware('scope:account,account_register')->only('store');
        $this->middleware('scope:account,account_update')->only('update');
        $this->middleware('scope:account,account_disable')->only('disable');
        $this->middleware('scope:account,account_enable')->only('enable');

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
            $user->client = 0;
            $user->password = Hash::make($temp_password);
            $user->save();

            $user->roles()->syncWithoutDetaching($request->role);
        });

        StoreEmployeeEvent::dispatch($this->authenticated_user());

        Notification::send($user, new CreatedNewUser($temp_password));

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

            if ($this->is_diferent($user->name, $request->name)) {
                $this->can_update[] = true;
                $user->name = $request->name;
            }

            if ($this->is_diferent($user->last_name, $request->last_name)) {
                $this->can_update[] = true;
                $user->last_name = $request->last_name;
            }

            if ($this->is_diferent($user->email, $request->email)) {
                $this->can_update[] = true;
                $user->email = $request->email;
            }

            if ($this->is_diferent($user->country, $request->country)) {
                $this->can_update[] = true;
                $user->country = $request->country;
            }

            if ($this->is_diferent($user->city, $request->city)) {
                $this->can_update[] = true;
                $user->city = $request->city;
            }

            if ($this->is_diferent($user->address, $request->address)) {
                $this->can_update[] = true;
                $user->address = $request->address;
            }

            if ($this->is_diferent($user->birthday, $request->birthday)) {
                $this->can_update[] = true;
                $user->birthday = $request->birthday;
            }

            if ($this->is_diferent($user->phone, $request->phone)) {
                $this->can_update[] = true;
                $user->phone = $request->phone;
            }

            if (in_array(true, $this->can_update)) {
                $user->push();
            }

        });

        if (in_array(true, $this->can_update)) {
            UpdateEmployeeEvent::dispatch();
        }

        return $this->showOne($user, $user->transformer, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $user)
    {

    }

    /**
     * deshabilita un usuario
     * @return Bool
     */
    public function disable(Request $request, Employee $user)
    {
        if (!$user->isClient() and $request->user()->id == $user->id) {
            throw new ReportError(__("Can´t disable by yourself"), 406);
        }

        $tokens = $user->tokens;

        $this->removeCredentials($tokens);

        $user->delete();

        //send an email to notify to remove his account

        DisableEmployeeEvent::dispatch();

        return $this->showOne($user, $user->transformer);
    }

    /**
     * habilita un usuario deshabilitado
     * @return Json
     */
    public function enable($id)
    {
        if (request()->user()->isClient()) {
            throw new ReportError("Unauthorize", 403);
        }

        try {

            $user = Employee::onlyTrashed()->find($id);

            $user->restore();

            EnableEmployeeEvent::dispatch();

            return $this->showOne($user, $user->transformer);

        } catch (Error $e) {
            throw new ReportError("Error al procesar la petición", 404);
        }
    }

    /**
     * deny acction if the user is a client
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
