<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Captcha extends Component
{

    /**
     * Provider
     * @var 
     */
    public $provider;

    /**
     * List of providers available
     * @var 
     */
    public $providers;

    /**
     * Site key 
     * @var 
     */
    public $siteKey;

    /**
     * Driver status 
     * @var 
     */
    public $status;

    /**
     * Loading only links
     */
    public $only_links;

    /**
     * Create a new component instance.
     */
    public function __construct($onlyLinks = false)
    {
        $this->provider = config("services.captcha.driver");
        $this->siteKey = config("services.captcha.providers.$this->provider.sitekey");
        $this->status = config("services.captcha.enabled");
        $this->providers = array_keys(config('services.captcha.providers'));
        $this->only_links = $onlyLinks;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.captcha');
    }
}
