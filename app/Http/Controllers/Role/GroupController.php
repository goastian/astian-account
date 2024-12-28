<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\GlobalController as Controller;
use App\Models\User\Group;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        /*$this->middleware('scope:group_read')->only('index', 'show');
        $this->middleware('scope:gruop_create')->only('store');
        $this->middleware('scope:group_update')->only('update');
        $this->middleware('scope:group_destroy')->only('destroy');*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Group $group)
    {
        $params = $this->filter_transform($group->transformer);

        $groups = $this->search($group->table, $params);

        return $this->showAll($groups, $group->transformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Group $group)
    {
        $this->validate($request, [
            'name' => ['required', 'max:100'],
            'description' => ['required', 'max:200'],
        ]);

        DB::transaction(function () use ($request, $group) {
            $group = $group->fill($request->all());
            $group->save();

            $this->privateChannel("GroupCreated", "New group created");
        });

        return $this->showOne($group, $group->transformer, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return $this->showOne($group, $group->transformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $this->validate($request, [
            'description' => ['nullable', 'max:200'],
        ]);

        DB::transaction(function () use ($request, $group) {

            if ($this->is_diferent($group->description, $request->description)) {
                $group->description = $request->description;

                $group->push();
                
                $this->privateChannel("GroupUpdated", "Group updated");
            }
        });

        return $this->showOne($group, $group->transformer, 201);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        collect(Group::groupByDefault())->map(function ($value, $key) use ($group) {
            throw_if($value->name == $group->name, new ReportError(__("Cannot delete this $value->name group"), 403));
        });

        throw_if(count($group->users()->get()) > 0, new ReportError("Cannot delete this group ", 403));

        $group->delete();

        $this->privateChannel("GroupDeleted", "Group deleted");

        return $this->showOne($group, $group->transformer);
    }
}
