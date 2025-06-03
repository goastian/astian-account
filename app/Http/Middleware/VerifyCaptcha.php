<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Captcha\Captcha;
use Symfony\Component\HttpFoundation\Response;

class VerifyCaptcha
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //Verify captcha
        if (config('services.captcha.enabled', false)) {

            $captcha = (new Captcha());

            if (!$captcha->verify()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => __('Captcha verification failed.')
                    ], 400);
                }

                return redirect()->back()->with([
                    'error' => __('Captcha verification failed.')
                ])->withInput();
            }

        }
        return $next($request);
    }
}
