<?php

namespace App\Http\Controllers\User;

use Error;
use App\Models\User\Employee;
use App\Exceptions\ReportMessage;
use Illuminate\Support\Facades\DB;
use App\Events\Employee\StoreEmployeeEvent;
use App\Events\Employee\EnableEmployeeEvent;
use App\Events\Employee\UpdateEmployeeEvent;
use App\Http\Requests\Employee\StoreRequest;
use Illuminate\Support\Facades\Notification;
use App\Events\Employee\DisableEmployeeEvent;
use App\Http\Requests\Employee\UpdateRequest;
use App\Notifications\Employee\CreatedNewUser;
use App\Transformers\Auth\EmployeeTransformer;
use App\Events\Employee\SendEmailStoreUserEvent;
use App\Http\Controllers\GlobalController as Controller;

class UserController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('transform.request:' . EmployeeTransformer::class)->only('store', 'update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Employee $user)
    {
        $params = $this->transformFilter($user->transformer);

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
        $temp_password = $this->passwordTempGenerate();

        DB::transaction(function () use ($request, $user, $temp_password) {

            $user = $user->fill($request->except('password', 'role'));
            $user->password = $temp_password;
            $user->save();

            $user->roles()->syncWithoutDetaching($request->role);
        });

        StoreEmployeeEvent::dispatch($this->AuthKey());

        Notification::send($user, new CreatedNewUser());

        return $this->showOne($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Employee::withTrashed()->find($id);

        return $this->showOne($user);
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

            $user->name = $request->name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->document_type = $request->document_type;
            $user->document_number = $request->document_number;
            $user->last_name = $request->last_name;
            $user->country = $request->country;
            $user->department = $request->department;
            $user->address = $request->address;

            $user->push();
        });

        UpdateEmployeeEvent::dispatch($this->AuthKey());

        return $this->showOne($user, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function disable(Employee $user)
    {
        $user->delete();

        DisableEmployeeEvent::dispatch($this->AuthKey());

        return $this->showOne($user);
    }

    public function enable($id)
    {
        try {

            $user = Employee::onlyTrashed()->find($id);
            
            $user->restore();
            
            EnableEmployeeEvent::dispatch($this->AuthKey());
            
            return $this->showOne($user);
            
        }catch(Error $e){
            throw new ReportMessage("Error al procesar la petición", 404);
        }
    }
}
