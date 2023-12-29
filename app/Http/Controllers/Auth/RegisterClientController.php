<?php

namespace App\Http\Controllers\Auth;

use DateTime;
use DateInterval;
use ErrorException;
use Illuminate\Http\Request;
use App\Models\User\Employee;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Events\Client\StoreClientEvent;
use App\Notifications\Client\ClientRegistered;
use Elyerr\ApiResponse\Exceptions\ReportError;

class RegisterClientController extends Controller
{

    public function __construct(Employee $client)
    {
        $this->middleware('transform.request:' . $client->transformer)->only('store');
    }
    /**
     * show view to register information
     */
    public function register()
    {
        return view('client.register');
    }

    /**
     *
     * @param Request $request
     */
    public function store(Request $request, Employee $client)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:employees,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'country' => ['nullable', 'max:100'],
            'city' => ['nullable', 'max:100'],
            'address' => ['nullable', 'max:150'],
            'phone' => ['nullable', 'max:25'],
            'birthday' => ['nullable', 'date_format:Y-m-d'],
        ]);

        DB::transaction(function () use ($request, $client) {

            $client = $client->fill($request->all());
            $client->password = Hash::make($request->password);
            $client->save();
            
            $client->notify(new ClientRegistered());
            StoreClientEvent::dispatch($client);

        });

        return $this->message(__('we send an email to verify your account.'), 201);
    }

    /**
     *
     * @param Request $request
     * @param Employee $user
     */
    public function verify_account(Request $request, Employee $user)
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

            return redirect()->route('login')->with(['message' => __('Your account has been activated.')]);
        } catch (ErrorException $e) {
            throw new ReportError(__("invalid credentials."), 403);
        }

    }
}
