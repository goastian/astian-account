<?php
namespace App\Http\Controllers\Web\Admin\User;

use App\Models\User\User;
use App\Models\User\UserScope;
use App\Repositories\UserRepository;
use App\Http\Controllers\WebController;
use App\Http\Requests\UserScope\StoreRequest;

class UserScopeController extends WebController
{

    /**
     * User repository
     */
    public $repository;

    /**
     * Construct of class
     */
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->repository = $userRepository;
        $this->middleware('userCanAny:administrator:user:full,administrator:user:view')->only('index');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:assign')->only('assign');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:revoke')->only('revoke');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:history')->only('history');
    }

    /**
     * Search users groups
     * @param string $user_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(string $user_id)
    {
        return $this->repository->searchScopesForUser($user_id);
    }

    /**
     * Create new scope for the user
     * @param \App\Http\Requests\UserScope\StoreRequest $request
     * @param string $user_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assign(StoreRequest $request, User $user)
    {
        return $this->repository->assignScopeForUser($user->id, $request->toArray());
    }

    /**
     * Revoke scope
     * @param \App\Models\User\User $user
     * @param \App\Models\User\UserScope $scope
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function revoke(User $user, UserScope $scope)
    {
        return $this->repository->revokeScopeForUser($user->id, $scope->id);
    }

    /**
     * Show the history the all scopes 
     * @param \App\Models\User\User $user
     * @param \App\Models\User\UserScope $userScope
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function history(User $user, UserScope $userScope)
    {
        return $this->repository->searchScopeHistoryForUser($user->id);
    }
}
