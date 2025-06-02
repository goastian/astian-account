<?php
namespace App\Services\Captcha\Interface;

interface CaptchaProviderInterface
{
    /**
     * Verify captcha
     *
     * @return bool
     */
    public function verify();
}
