<?php
namespace App\Http\Controllers\Subscription;

use Illuminate\Http\Request;
use App\Models\Subscription\Scope;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\GlobalController;

class ScopeController extends GlobalController
{

    /**
     * constructor of class
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:administrator_scope_full,administrator_scope_view')->only('index');
        $this->middleware('scope:administrator_scope_full,administrator_scope_show')->only('show');
        $this->middleware('scope:administrator_scope_full,administrator_scope_create')->only('store');
        $this->middleware('scope:administrator_scope_full,administrator_scope_update')->only('update');
        $this->middleware('scope:administrator_scope_full,administrator_scope_destroy')->only('destroy');
    }

    /**
     * Show the all resources for scopes
     * @param \App\Models\Subscription\Scope $scope
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Scope $scope)
    {
        $this->checkMethod('get');

        $params = $this->filter_transform($scope->transformer);

        $data = $scope->query();

        $this->searchByBuilder($data, $params);

        return $this->showAllByBuilder($data, $scope->transformer);
    }

    /**
     * Create new resource
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subscription\Scope $scope
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Scope $scope)
    {
        $scopeExists = Scope::where('role_id', $request->role_id)
            ->where('service_id', $request->service_id)->first();

        $this->validate($request, [
            'service_id' => [
                'required',
                'exists:services,id',
                function ($attribute, $value, $fail) use ($scopeExists) {
                    if ($scopeExists) {
                        $fail(__("The scope has been registered and can't be added again"));
                    }
                }
            ],
            'role_id' => [
                'required',
                'exists:roles,id',
                function ($attribute, $value, $fail) use ($scopeExists) {
                    if ($scopeExists) {
                        $fail(__("The scope has been registered and can't be added again"));
                    }
                }
            ],
            'public' => ['nullable', 'boolean'],
            'active' => ['nullable', 'boolean'],
            'api_key' => ['nullable', 'boolean'],
        ]);

        $this->checkMethod('post');
        $this->checkContentType($this->getPostHeader());

        DB::transaction(function () use ($request, $scope) {

            $scope = $scope->fill($request->all());
            $scope->save();

        });

        return $this->showOne($scope, $scope->transformer, 201);
    }

    /**
     * Show scope resource
     * @param \App\Models\Subscription\Scope $scope
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Scope $scope)
    {
        $this->checkMethod('get');
        $this->checkContentType(null);

        return $this->showOne($scope, $scope->transformer, 201);
    }

    /**
     * Update scope resource
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subscription\Scope $scope
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Scope $scope)
    {
        $this->validate($request, [
            'service_id' => ['nullable', 'exists:services,id'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'public' => ['nullable', 'boolean'],
            'active' => ['nullable', 'boolean'],
            'api_key' => ['nullable', 'boolean'],

        ]);

        $this->checkMethod("put");
        $this->checkContentType($this->getUpdateHeader());

        DB::transaction(function () use ($request, $scope) {

            $update = false;

            if ($request->has('service_id') && $scope->service_id != $request->service_id) {
                $update = true;
                $scope->service_id = $request->service_id;
            }

            if ($request->has('role_id') && $scope->role_id != $request->role_id) {
                $update = true;
                $scope->role_id = $request->role_id;
            }

            if ($request->has('public') && $scope->public != $request->public) {
                $update = true;
                $scope->public = (int) $request->public;
            }

            if ($request->has('active') && $scope->active != $request->active) {
                $update = true;
                $scope->active = (int) $request->active;
            }

            if ($request->has('api_key') && $scope->api_key != $request->api_key) {
                $update = true;
                $scope->api_key = (int) $request->api_key;
            }

            if ($update) {
                $scope->push();
            }
        });

        return $this->showOne($scope, $scope->transformer, 200);
    }

    /**
     * destroy resource
     * @param \App\Models\Subscription\Scope $scope
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Scope $scope)
    {
        $this->checkMethod("delete");
        $this->checkContentType(null);

        $scope->delete();

        return $this->showOne($scope, $scope->transformer);
    }
}
