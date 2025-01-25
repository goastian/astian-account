<?php

namespace App\Http\Controllers\Auth;

use DateTime;
use DateInterval;
use ErrorException;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Elyerr\ApiResponse\Exceptions\ReportError;
use App\Notifications\User\VendorCreatedAccount;

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
     * @param \App\Models\User\User $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, User $client)
    {
        $this->checkMethod('post');
        $this->checkContentType($this->getPostHeader());

        $this->validate($request, [
            'name' => ['required', 'regex:/^[A-Za-z\s]+$/', 'min:3', 'max:100'],
            'last_name' => ['required', 'regex:/^[A-Za-z\s]+$/', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8', 'max:60'],
            'birthday' => ['required', 'date_format:Y-m-d', 'before: ' . User::setBirthday()],
            'accept_terms' => ['required', 'boolean']
        ]);

        DB::transaction(function () use ($request, $client) {

            $client = $client->fill($request->all());
            $client->password = Hash::make($request->password);
            $client->save();

            $client->notify(new VendorCreatedAccount());

            $this->privateChannel("StoreClientEvent", "New client registered");
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
            $now->add(new DateInterval("PT" . env("TIME_TO_VERIFY_ACCOUNT", 5) . "M"));
            $date = $now->format("Y-m-d H:i:s");

            DB::table('password_resets')->where('email', '=', $request->email)->delete();

            $user = $user->where('email', $request->email)->first();

            if (date('Y-m-d H:i:s', strtotime(now())) > $date) {
                $user->forceDelete();
                throw new ReportError(__("Time's up to activate the account"), 403);
            }

            $user->verified_at = now();
            $user->save();

            return redirect()->route('login')->with(['status' => __('Your account has been activated.')]);
        } catch (ErrorException $e) {
            return redirect()->route('login')->with(['error' => __('Verification failed. The token has expired.')]);
        }

    }

    /**
     * Show view to verify  account 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function formVerifyAccount()
    {
        if (auth()->check() && auth()->user()->verified_at) {
            return redirect('/');
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

            auth()->user()->notify(new VendorCreatedAccount());

            return back()->with("status", "we're sent an a new email to verify your account");
        }

        return redirect("/");
    }
}
