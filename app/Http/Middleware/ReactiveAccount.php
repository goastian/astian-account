<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;
use App\Events\User\EnableEmployeeEvent;
use App\Notifications\Client\ReactiveAccount as Notification;

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

        if ($user) {

            DB::transaction(function() use($user){

                $user->deleted_at = null;
                
                $user->push();
                
                EnableEmployeeEvent::dispatch();
                
                $user->notify(new Notification());
                
            });
        }

        return $next($request);
    }
}
