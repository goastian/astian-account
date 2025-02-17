<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\Member\MemberReactivateAccount;

class ReactiveAccount
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
        $user = User::onlyTrashed()->where('email', $request->email)->first();

        if ($user && $user->hasGroup('member')) {

            DB::transaction(function () use ($user) {

                $user->deleted_at = null;

                $user->push();

                $user->notify(new MemberReactivateAccount());
            });
        }

        return $next($request);
    }
}
