<?php
namespace App\Http\Controllers\Web\Admin\User;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Models\Subscription\Group;
use App\Http\Controllers\WebController;
use App\Transformers\User\UserGroupTransformer;

class UserGroupController extends WebController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator_user_full,administrator_user_view')->only('index');
        $this->middleware('userCanAny:administrator_user_full,administrator_user_assign')->only('assign');
        $this->middleware('userCanAny:administrator_user_full,administrator_user_revoke')->only('revoke');

        $this->middleware('wants.json')->only('index');
    }

    public function index(User $user)
    {
        $groups = $user->groups()->get();

        return $this->showAll($groups, UserGroupTransformer::class);
    }

    /**
     * Add new groups from $request
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assign(Request $request, User $user)
    {
        $this->validate($request, [
            'groups' => [
                'required',
                function ($attribute, $value, $fail) {

                    if (empty($value)) {
                        $fail(__('The groups field is required.'));
                    }

                    if (!is_array($value)) {
                        $fail(__('The groups field must be an array.'));
                    }

                    foreach ($value as $key) {
                        $group = Group::find($key);

                        if (empty($group)) {
                            $fail(__('The group with ID :key does not exist.', ['key' => $key]));
                        }
                    }
                }
            ],
        ]);

        $user->groups()->syncWithoutDetaching($request->groups);

        return $this->message(__('Groups assigned successfully'), 201);
    }

    /**
     * Revoke groups
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User\User $user
     * @param \App\Models\Subscription\Group $group
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function revoke(Request $request, User $user, Group $group)
    {
        $group->users()->detach($user->id);

        return $this->message(__('Groups revoked successfully'), 200);
    }

}
