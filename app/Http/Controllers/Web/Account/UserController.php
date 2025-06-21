<?php
namespace App\Http\Controllers\Web\Account;

use Inertia\Inertia; 
use App\Repositories\UserRepository; 
use App\Http\Controllers\WebController;
use App\Http\Requests\User\PersonalUpdateRequest;
use App\Http\Requests\User\PersonalPasswordRequest;

class UserController extends WebController
{
    /**
     * User repository
     * @var UserRepository
     */
    public $repository;

    /**
     * Construct
     * @param \App\Repositories\UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->repository = $userRepository;
    }

    /**
     * Show the form to updated information
     * @return \Inertia\Response
     */
    public function profile()
    {
        return Inertia::render("Account/Information");
    }

    /**
     * Update personal information for the user
     * @param \App\Http\Requests\User\PersonalUpdateRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function personalInformation(PersonalUpdateRequest $request)
    {
        return $this->repository->updatePersonalInformation($request->toArray());
    }

    /**
     * Show the form to update password
     * @return \Inertia\Response
     */
    public function formToChangePassword()
    {
        return Inertia::render("Account/Password");
    }

    /**
     * Change password
     * @param \App\Http\Requests\User\PersonalPasswordRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function changePassword(PersonalPasswordRequest $request)
    {
        return $this->repository->updatePersonalPassword($request->toArray());

    }
}
