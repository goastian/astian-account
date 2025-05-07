<?php

namespace App\Http\Controllers\Auth;

use DateTime;
use DateInterval;
use ErrorException;
use App\Models\User\User;
use App\Rules\BooleanRule;
use Illuminate\Http\Request;
use App\Models\Subscription\Group;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Elyerr\ApiResponse\Exceptions\ReportError;
use App\Notifications\User\VendorCreatedAccount;
use App\Notifications\Member\MemberCreatedAccount;

class RegisterClientController extends Controller
{

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
     * Register users
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, User $user)
    {
        $this->checkMethod('post');
        $this->checkContentType($this->getPostHeader());

        $this->validate($request, [
            'name' => ['required', 'regex:/^[A-Za-z\s]+$/', 'min:3', 'max:100'],
            'last_name' => ['required', 'regex:/^[A-Za-z\s]+$/', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8', 'max:60'],
            'birthday' => ['required', 'date_format:Y-m-d', 'before: ' . User::setBirthday()],
            'accept_terms' => ['required', new BooleanRule()]
        ]);

        $group = Group::where('slug', 'member')->first();

        if (empty($group)) {
            return back()->with('error', __('The registration could not be completed successfully. Our team has been notified of the issue and is working to resolve it. We appreciate your patience and encourage you to try again later'));
        }

        DB::transaction(function () use ($request, $user, $group) {
            $user = $user->fill($request->all());
            $user->password = Hash::make($request->password);
            $user->save();

            $user->groups()->attach($group->id);

            $user->notify(new MemberCreatedAccount());
        });

        return redirect()->route('login')->with('status', __('Your account has been registered successfully. A verification email has been sent to your inbox.'));
    }

    /**
     * Check credential to activate the account
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User\User $user
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyAccount(Request $request, User $user)
    {
        try {

            $data = DB::table('password_resets')->where([
                'token' => $request->token,
                'email' => $request->email,
            ])->first();

            $now = new DateTime($data->created_at);
            $now->add(new DateInterval("PT" . config("system.verify_account_time", 5) . "M"));
            $date = $now->format("Y-m-d H:i:s");

            DB::table('password_resets')->where('email', '=', $request->email)->delete();

            $user = $user->where('email', $request->email)->first();

            if (date('Y-m-d H:i:s', strtotime(now())) > $date) {
                $user->forceDelete();
                throw new ReportError(__("Time's up to activate the account"), 403);
            }

            $user->verified_at = now();
            $user->save();

            $token = uniqid();

            return redirect()->route('users.verified.account')->with(
                [
                    'status' => __('Your account has been activated.'),
                    'token' => $token
                ]
            );

        } catch (ErrorException $e) {
            if (auth()->check()) {
                auth()->logout();
            }

            return redirect()->route('login');
        }

    }

    /**
     * Show view to verify  account 
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
            return view('auth.verified-account');
        }
        return redirectToHome();
    }
}
