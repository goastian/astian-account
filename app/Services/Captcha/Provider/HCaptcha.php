<?php
namespace App\Services\Captcha\Provider;

use Illuminate\Support\Facades\Http;
use App\Services\Captcha\Interface\CaptchaProviderInterface;


class HCaptcha implements CaptchaProviderInterface
{

    /**
     * Verify captcha
     * @return bool
     */
    public function verify()
    {
        $api = config('services.captcha.providers.hcaptcha.api');
        $secret = config('services.captcha.providers.hcaptcha.secret');

        $token = request()->input('h-captcha-response');

        $response = Http::asForm()->post($api, [
            'secret' => $secret,
            'response' => $token,
            'remoteip' => request()->ip(),
        ]);

        $body = $response->json();

        return $body['success'];
    }
}
