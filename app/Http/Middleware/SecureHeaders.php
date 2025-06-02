<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecureHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $nonce = $this->generateNonce();

        view()->share('nonce', $nonce);
        $response = $next($request);
        
        if (config('system.csp_enabled', true)) {

            $response->headers->set("Referrer-Policy", "no-referrer");
            $response->headers->set("X-Content-Type-Options", "nosniff");
            $response->headers->set("X-Frame-Options", "DENY");
            $response->headers->set("Permissions-Policy", "accelerometer=(), autoplay=(), camera=(), encrypted-media=(), fullscreen=(self), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), speaker=(self), display-capture=()");
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

            //Ignore csp policies in this route
            if (!in_array($request->route()->getName(), ['passport.authorizations.authorize'])) {
                $response->headers->set("Content-Security-Policy", $this->ContentSecurityPolicy($nonce));
            }
        }

        return $response;
    }

    /**
     * Setting default content security policies 
     * @return string
     */
    public function ContentSecurityPolicy($nonce)
    {
        $policies = [
            "base-uri 'self'",
            "script-src 'self'",
            "script-src-elem 'self' 'nonce-{$nonce}'",
            "script-src-attr 'self' 'nonce-{$nonce}'",
            // "style-src 'self' $host 'unsafe-inline'",
            // "style-src-elem 'self' $host 'unsafe-inline'",
            "style-src-attr 'self' 'unsafe-inline' 'nonce-{$nonce}'",
            "media-src 'self'",
            "object-src 'self'",
            "child-src 'self'",
            "frame-src 'self' https://newassets.hcaptcha.com/ https://challenges.cloudflare.com",
            "frame-ancestors 'self'",
            "img-src 'self' data:",
            "font-src 'self'",
            //"connect-src 'self'",
            "form-action *",
            "worker-src *",
            "manifest-src 'self'",
            "upgrade-insecure-requests",
        ];

        return implode(";", $policies);
    }

    /**
     * Generate a secure code
     * @return string
     */
    public function generateNonce()
    {
        return bin2hex(random_bytes(16));
    }
}
