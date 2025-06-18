<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User\User;
use App\Models\Setting\Code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Notifications\Setting\CodeNotification;

class Auth2faMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() and User::validate($request)) {

            Auth2faMiddleware::generateToken($request);

            session(['email' => $request->email]);

            return redirect()->route('users.2fa.send-code');
        }

        return $next($request);
    }

    /**
     * Store a new token
     * 
     * @param Request $request
     */
    public static function generateToken(Request $request)
    {
        $email = $request->email ?: $request->user()->email;

        $user = User::where('email', $email)->first();

        Code::create([
            'status' => $request->session()->getId(),
            'email' => $user->email,
            'code' => Hash::make($code = mt_rand(100000, 999999)),
            'created_at' => now(),
        ]);

        $user->notify(new CodeNotification($code));
    }
}
