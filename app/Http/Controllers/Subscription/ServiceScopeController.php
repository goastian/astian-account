<?php

namespace App\Http\Controllers\Subscription;

use App\Rules\BooleanRule;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;
use App\Models\Subscription\Scope;
use Illuminate\Support\Facades\DB;
use App\Models\Subscription\Service;
use App\Http\Controllers\GlobalController;
use App\Transformers\Subscription\ServiceScopeTransformer;

class ServiceScopeController extends GlobalController
{
    /**
     * Construct 
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:administrator_service_full,administrator_service_view')->only('index');
        $this->middleware('scope:administrator_service_full,administrator_service_assign')->only('assign');
        $this->middleware('scope:administrator_service_full,administrator_service_revoke')->only('revoke');
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

        return $this->showAll($scopes, ServiceScopeTransformer::class);
    }


    /**
     * Add roles 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subscription\Service $service 
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assign(Request $request, Service $service)
    {
        $this->validate($request, [
            'role_id' => ['required', 'exists:roles,id'],
            'public' => ['required', new BooleanRule()],
            'active' => ['required', new BooleanRule()],
            'api_key' => ['required', new BooleanRule()],
        ]);

        $this->checkMethod('post');
        $this->checkContentType($this->getPostHeader());

        DB::transaction(function () use ($request, $service) {
            $service->scopes()->updateOrCreate(
                [
                    'role_id' => $request->role_id,
                ],
                [
                    'role_id' => $request->role_id,
                    'public' => $request->public,
                    'active' => $request->active,
                    'api_key' => $request->api_key
                ]
            );
        });

        return $this->message(__('Role has been added successfully'), 201);
    }

    /**
     * Summary of revoke
     * @param \App\Models\Subscription\Service $service
     * @param \App\Models\Subscription\Scope $scope
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function revoke(Service $service, Scope $scope)
    {
        if ($service->id != $scope->service_id) {
            throw new ReportError(__("This action is invalid"), 400);
        }
        try {
            $scope->delete();
        } catch (\Throwable $th) {
            throw new ReportError(__("This resource cannot be deleted because it is being used by another resource."), 400);
        }

        return $this->message(__('Role has been revoked successfully'), 201);
    }
}
