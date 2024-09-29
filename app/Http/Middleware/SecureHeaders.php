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
        //Generate a secure nonce
        $nonce = $this->generateNonce();
        // Global key in the all views laravel
        view()->share('nonce', $nonce);

        $response = $next($request);

        $response->headers->set("Referrer-Policy", "no-referrer");
        $response->headers->set("X-Content-Type-Options", "nosniff");
        $response->headers->set("X-Frame-Options", "DENY");
        $response->headers->set("Permissions-Policy", "accelerometer=(), autoplay=(), camera=(), encrypted-media=(), fullscreen=(self), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), speaker=(self), display-capture=()");
        $response->headers->set("Strict-Transport-Security", "max-age=31536000");

        //Ignore csp policies int this route
        if (!in_array($request->route()->getName(), ['passport.authorizations.authorize'])) {
            $response->headers->set("Content-Security-Policy", $this->ContentSecurityPolicy($nonce));
        }

        return $response;
    }

    /**
     * Settting default content security policies
     *
     * @param String $nonce
     * @return String
     */
    public function ContentSecurityPolicy($nonce)
    {
        $host = request()->getHost();

        $policies = [
            "base-uri 'self'",
            "script-src 'self' 'nonce-{$nonce}'",
            "script-src-elem 'self' 'nonce-{$nonce}'",
            "script-src-attr 'self'",
            // "style-src 'self' 'nonce-{$nonce}' $host 'unsafe-inline'",
            // "style-src-elem 'self' 'nonce-{$nonce}' $host 'unsafe-inline'",
            "style-src-attr 'self' $host 'unsafe-inline'",
            "media-src 'self'",
            "object-src 'self'",
            "child-src 'self'",
            "frame-src 'self'",
            "frame-ancestors 'self'",
            "img-src 'self' data:",
            "font-src 'self'",
            //"connect-src 'self'",
            "form-action *",
            "worker-src *",
            "manifest-src 'self'",
            "upgrade-insecure-requests",
            "block-all-mixed-content",
        ];

        return implode(";", $policies);
    }

    /**
     * Generate a secure code
     *
     * @return String
     */
    function generateNonce()
    {
        return bin2hex(random_bytes(16));
    }
}
