<?php

namespace App\Http\Controllers\User;

use Error;
use App\Models\User\Employee; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Events\Employee\StoreEmployeeEvent;
use App\Events\Employee\EnableEmployeeEvent;
use App\Events\Employee\UpdateEmployeeEvent;
use App\Http\Requests\Employee\IndexRequest;
use App\Http\Requests\Employee\ShowRequest; 
use App\Http\Requests\Employee\StoreRequest;
use Illuminate\Support\Facades\Notification;
use App\Events\Employee\DisableEmployeeEvent;
use App\Http\Requests\Employee\EnableRequest;
use App\Http\Requests\Employee\UpdateRequest;
use App\Http\Requests\Employee\DisableRequest;
use App\Notifications\Employee\CreatedNewUser;
use Elyerr\ApiResponse\Exceptions\ReportError;
use App\Http\Controllers\GlobalController as Controller;

class UserController extends Controller
{

    public function __construct(Employee $user)
    {
        parent::__construct(); 
        $this->middleware('transform.request:' . $user->transformer)->only('store', 'update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request, Employee $user)
    {
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
        $temp_password = $this->passwordTempGenerate();

        DB::transaction(function () use ($request, $user, $temp_password) {

            $user = $user->fill($request->except('password', 'role'));
            $user->password = Hash::make($temp_password);
            $user->save();

            $user->roles()->syncWithoutDetaching($request->role);
        });

        StoreEmployeeEvent::dispatch($this->AuthKey());

        Notification::send($user, new CreatedNewUser($temp_password));

        return $this->showOne($user, $user->transformer, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request, $id)
    {
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

            if (request()->user()->tokenCan('admin') && $this->is_diferent($user->name, $request->name)) {
                $this->can_update[] = true;
                $user->name = $request->name;
            }

            if (request()->user()->tokenCan('admin') && $this->is_diferent($user->last_name, $request->last_name)) {
                $this->can_update[] = true;
                $user->last_name = $request->last_name;
            }

            if ($this->can_access() && $this->is_diferent($user->email, $request->email)) {
                $this->can_update[] = true;
                $user->email = $request->email;
            }

            if (request()->user()->tokenCan('admin') && $this->is_diferent($user->document_type, $request->document_type)) {
                $this->can_update[] = true;
                $user->document_type = $request->document_type;
            }

            if (request()->user()->tokenCan('admin') && $this->is_diferent($user->document_number, $request->document_number)) {
                $this->can_update[] = true;
                $user->document_number = $request->document_number;
            }
 
            if (request()->user()->tokenCan('admin') && $this->is_diferent($user->country, $request->country)) {
                $this->can_update[] = true;
                $user->country = $request->country;
            }

            if (request()->user()->tokenCan('admin') && $this->is_diferent($user->department, $request->department)) {
                $this->can_update[] = true;
                $user->department = $request->department;
            }

            if (request()->user()->tokenCan('admin') && $this->is_diferent($user->address, $request->address)) {
                $this->can_update[] = true;
                $user->address = $request->address;
            }

            if ($this->can_access() && $this->is_diferent($user->phone, $request->phone)) {
                $this->can_update[] = true;
                $user->phone = $request->phone;
            }

            if (in_array(true, $this->can_update)) {
                $user->push();
            }

        });

        if (in_array(true, $this->can_update)) {
            UpdateEmployeeEvent::dispatch($this->AuthKey());
        }
        
        return $this->showOne($user, $user->transformer, 201);
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

    public function disable(DisableRequest $request, Employee $user)
    {
        $user->delete();

        DisableEmployeeEvent::dispatch($this->AuthKey());

        return $this->showOne($user, $user->transformer);
    }

    public function enable(EnableRequest $request, $id)
    {
        try {

            $user = Employee::onlyTrashed()->find($id);

            $user->restore();

            EnableEmployeeEvent::dispatch($this->AuthKey());

            return $this->showOne($user, $user->transformer);

        } catch (Error $e) {
            throw new ReportError("Error al procesar la petición", 404);
        }
    }

    public function can_access(){
        return request()->user()->tokenCan('admin') || request()->user->id == request()->user()->id;
    }
}
