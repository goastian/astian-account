<?php
namespace App\Services\Captcha;

use App\Services\Captcha\Provider\HCaptcha;
use Illuminate\Support\Facades\App;
use App\Services\Captcha\Provider\CloudflareTurnstile;
use App\Services\Captcha\Interface\CaptchaProviderInterface;

class Captcha
{
    /**
     * Summary of provider
     * @var \App\Services\Captcha\Interface\CaptchaProviderInterface
     */
    protected CaptchaProviderInterface $provider;

    /**
     * Drivers
     * @var array
     */
    protected $drivers = [
        'turnstile' => CloudflareTurnstile::class,
        'hcaptcha' => HCaptcha::class
    ];

    /** 
     * @throws \Exception
     */
    public function __construct()
    {
        $driverKey = config('services.captcha.driver');

        if (!array_key_exists($driverKey, $this->drivers)) {
            throw new \Exception("Unsupported captcha driver: {$driverKey}");
        }

        $driver = $this->drivers[$driverKey];

        $this->provider = app()->make($driver);

    }

    /**
     * Verify captcha
     * @return bool
     */
    public function verify(): bool
    {
        return $this->provider->verify();
    }
}
