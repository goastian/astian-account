<?php
namespace App\Services\Captcha\Provider;

use Illuminate\Support\Facades\Http;
use App\Services\Captcha\Interface\CaptchaProviderInterface;

class CloudflareTurnstile implements CaptchaProviderInterface
{
    /**
     * Verify captcha
     * @return bool
     */
    public function verify()
    {
        $api = config('services.captcha.providers.turnstile.api');
        $secret = config('services.captcha.providers.turnstile.secret');

        $token = request()->input('cf-turnstile-response');

        $response = Http::asForm()->post($api, [
            'secret' => $secret,
            'response' => $token,
            'remoteip' => request()->ip(),
        ]);

        $body = $response->json();
        
        return $body['success'];
    }
}
