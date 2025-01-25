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

        foreach ($params as $key => $value) {
            $data = $data->where($key, "LIKE", "%" . $value . "%");
        }

        $data = $data->get();

        return $this->showAll($data, $scope->transformer);
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
            'requires_payment' => ['nullable', 'boolean'],
            'public' => ['nullable', 'boolean'],
            'active' => ['nullable', 'boolean'],
            'price' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!is_numeric($value)) {
                        $fail(__("The :attribute is not a number"));
                    }
                }
            ]
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
        $this->checkMethod("put");
        $this->checkContentType($this->getUpdateHeader());

        $this->validate($request, [
            'service_id' => ['nullable', 'exists:services,id'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'requires_payment' => ['nullable', 'boolean'],
            'public' => ['nullable', 'boolean'],
            'active' => ['nullable', 'boolean'],
            'price' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!is_numeric($value)) {
                        $fail(__("The :attribute is not a number"));
                    }
                }
            ]
        ]);

        DB::transaction(function () use ($request, $scope) {

            $update = false;

            if ($this->is_different($scope->service_id, $request->service_id)) {
                $scope->service_id = $request->service_id;
                $update = true;
            }

            if ($this->is_different($scope->role_id, $request->role_id)) {
                $scope->role_id = $request->role_id;
                $update = true;
            }

            if ($this->is_different($scope->requires_payment, $request->requires_payment)) {
                $scope->requires_payment = $request->requires_payment;
                $update = true;
            }

            if ($this->is_different($scope->public, $request->public)) {
                $scope->public = $request->public;
                $update = true;
            }

            if ($this->is_different($scope->active, $request->active)) {
                $scope->active = $request->active;
                $update = true;
            }

            if ($this->is_different($scope->price, $request->price)) {
                $scope->price = $request->price;
                $update = true;
            }

            if ($update) { 
                $scope->push();
            }
        });

        return $this->showOne($scope, $scope->transformer, 201);
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
