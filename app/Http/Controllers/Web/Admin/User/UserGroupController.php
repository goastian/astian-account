<?php
namespace App\Http\Controllers\Web\Admin\User;

use App\Models\User\User;
use App\Models\Subscription\Group;
use App\Repositories\UserRepository; 
use App\Http\Controllers\WebController; 
use App\Http\Requests\UserGroup\StoreRequest;

class UserGroupController extends WebController
{
    /**
     * User repository
     * @var UserRepository
     */
    public $repository;

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->repository = $userRepository;
        $this->middleware('userCanAny:administrator:user:full,administrator:user:view')->only('index');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:assign')->only('assign');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:revoke')->only('revoke');
        $this->middleware('wants.json')->only('index');
    }

    /**
     * Show the all groups for the user
     * @param \App\Models\User\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(User $user)
    {
        return $this->repository->searchGroupsForUser($user->id);
    }

    /**
     * Assign group to the user
     * @param \App\Http\Requests\UserGroup\StoreRequest $request
     * @param \App\Models\User\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assign(StoreRequest $request, User $user)
    {
        return $this->repository->assignGroupForUser($user->id, $request->toArray());
    }

    /**
     * Revoke the groups to the user
     * @param \App\Models\User\User $user
     * @param \App\Models\Subscription\Group $group
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function revoke(User $user, Group $group)
    {
        return $this->repository->revokeGroupForUser($user->id, $group->id);
    }

}
