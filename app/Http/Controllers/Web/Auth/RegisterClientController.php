<?php

namespace App\Http\Controllers\Web\Auth;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Http\Controllers\WebController;
use App\Http\Requests\User\RegisterRequest;
use App\Notifications\Member\MemberCreatedAccount;

class RegisterClientController extends WebController
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
        $this->repository = $userRepository;
    }

    /**
     * Show view to register users
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function register()
    {
        if (request()->user()) {
            return redirect('/');
        }
        return view('client.register');
    }

    /**
     * Register new customers
     * @param \App\Http\Requests\User\RegisterRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RegisterRequest $request)
    {
        return $this->repository->registerCustomer($request->toArray());
    }

    /**
     * Verify user account
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function verifyAccount(Request $request)
    {
        return $this->repository->verifyUserAccount($request->toArray());
    }

    /**
     * Show form to verify user email account
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function formVerifyAccount()
    {
        if (auth()->check() && auth()->user()->verified_at) {
            return redirectToHome();
        }

        return view("auth.verify-account");
    }

    /**
     * Send verification email to the user 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendVerificationEmail()
    {
        if (!auth()->user()->verified_at) {

            auth()->user()->notify(new MemberCreatedAccount());

            return back()->with("status", "we're sent an a new email to verify your account");
        }

        return redirectToHome();
    }

    /**
     * Redirect to the user after activate your account
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function verifiedAccount()
    {
        if (session('token')) {
            session()->forget('token');
            return view('auth.verified-account');
        }

        return redirectToHome();
    }
}
