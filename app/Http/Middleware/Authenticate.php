<?php

namespace App\Http\Middleware;

use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if ($request->wantsJson()) {
            $uri = $_SERVER['REQUEST_URI'] ?? '';

            if (preg_match('#/gateway\b#', $uri)) {
                throw new ReportError('You are not logged in.', 401);
            }
        }

        // Only referral redirect to the login
        if (!empty($referral_code = $request->referral_code)) {
            return route('login', ['referral_code' => $referral_code]);
        }

        // For the rest of the params get the full url
        $next_page = $request->fullUrl();

        // Save url into the session
        session()->put('redirect_to', $next_page);

        return route('login');
    }
}
