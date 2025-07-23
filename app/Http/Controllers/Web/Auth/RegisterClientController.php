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
        $this->middleware('auth')->except('register', 'store', 'verifyAccount');
    }

    /**
     * Show view to register users
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function register(Request $request)
    {
        // If the request has a redirect_to parameter, store it in the session
        if (!empty($request->input('redirect_to'))) {
            session()->put('redirect_to', $request->input('redirect_to'));
        }
        
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
        $this->recoveryReferralCode($request); 
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
        if (auth()->user()->verified_at) {
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
        $redirect_to = session()->get('redirect_to');

        if (!empty($redirect_to)) {
            session()->forget('redirect_to');
            return redirect($redirect_to);
        }

        if (session('token')) {
            session()->forget('token');
            return view('auth.verified-account');
        }

        return redirectToHome();
    }

    /**
     * Recovery referral code from the redirect_to session
     * This method extracts the referral code from the redirect_to URL if it exists.
     * It checks if the redirect_to session variable is set, parses the URL,
     * and retrieves the referral_code from the query parameters.
     * If a referral code is found, it merges it into the request.
     * @return void
     */
    private function recoveryReferralCode(Request $request): void
    {
        $redirect_to = session()->get('redirect_to');
        $referral_code = null;
         
        if ($redirect_to) {
            $parsedUrl = parse_url($redirect_to);

            if (isset($parsedUrl['query'])) {
                parse_str($parsedUrl['query'], $query_params);
                $referral_code = $query_params['referral_code'] ?? null;
            }

            if ($referral_code) {
                $request->merge(['referral_code' => $referral_code]);
            }
        }
    }
}
