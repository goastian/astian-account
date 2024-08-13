<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\GlobalController as Controller;
use App\Models\User\Employee;
use App\Models\User\Group;
use App\Transformers\Auth\EmployeeGroupTransformaer;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserGroupController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:account_read')->only('index');
        $this->middleware('scope:account_create')->only('store');
        $this->middleware('scope:account_update')->only('destroy');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Employee $user)
    {
        $groups = $user->groups()->get();

        return $this->showAll($groups, EmployeeGroupTransformaer::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Employee $user, Group $group)
    {
        $this->validate($request, [
            'group_id' => ['required', 'exists:groups,id'],
        ]);

        DB::transaction(function () use ($request, $user) {
            $user->groups()->syncWithoutDetaching($request->group_id);
        });

        return $this->showOne($group->find($request->group_id), EmployeeGroupTransformaer::class, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $user, Group $group)
    {
        throw_if($user->id == auth()->user()->id, new ReportError(_("Cannot delete this resource"), 403));
        
        DB::transaction(function () use ($user, $group) {
            $user->groups()->detach($group->group_id);
        });

        return $this->showOne($group, EmployeeGroupTransformaer::class);
    }
}
