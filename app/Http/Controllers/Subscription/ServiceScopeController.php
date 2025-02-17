<?php

namespace App\Http\Controllers\Subscription;

use App\Models\Subscription\Service;
use App\Http\Controllers\GlobalController;
use App\Transformers\Subscription\ScopeTransformer;

class ServiceScopeController extends GlobalController
{
    /**
     * Construct 
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:administrator_service_full,administrator_service_view')->only('index');
    }

    /**
     * Show the all scope of the service
     * @param \App\Models\Subscription\Service $service
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Service $service)
    {
        $this->checkMethod('get');

        $scopes = $service->scopes()->get();

        return $this->showAll($scopes, ScopeTransformer::class);
    }
}
