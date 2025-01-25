<?php
namespace App\Http\Controllers\User;

use App\Models\User\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\User\UserScope;
use App\Models\Subscription\Scope;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\GlobalController;

class UserScopeController extends GlobalController
{
    /**
     * Construct of class
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the all resources
     * @param \App\Models\User\User $user
     * @param \App\Models\User\UserScope $userScope
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(User $user, UserScope $userScope, Scope $scope)
    {
        $this->checkMethod('get');

        $params = $this->filter_transform($userScope->transformer);

        $scopes = $userScope->query();

        $scopes = $userScope->where('user_id', $user->id)
            ->where(function ($query) {
                $query->where('end_date', '>', now())
                    ->orWhereNull('end_date');
            })
            ->whereHas('scope', function ($query) {
                $query->where('active', '!=', false);
            })
            ->with(['scope.service.group', 'scope.role']);

        $this->search($scopes, $params);

        $scopes = $scopes->get();

        return $this->showAll($scopes, $userScope->transformer);
    }

    /**
     * Create new scope
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User\User $user
     * @param \App\Models\User\UserScope $userScope
     * @param \App\Http\Controllers\User\Scope $scope
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request, User $user, UserScope $userScope, Scope $scope)
    {
        $this->validate($request, [
            'scopes' => [
                'required',
                function ($attribute, $value, $fail) use ($scope) {
                    if (!is_array($value)) {
                        $fail(__("The :attribute must be an array", ['attribute' => $attribute]));
                    }

                    if (is_array($value)) {
                        foreach ($value as $id) {
                            try {
                                $scopes = $scope->find($id);
                                if (!$scopes) {
                                    $fail(__("The :attribute is not valid", ["attribute" => $attribute, "id" => $id]));
                                }
                            } catch (QueryException) {
                                $fail(__("The :attribute is not valid", ["attribute" => $attribute, "id" => $id]));
                            }
                        }
                    }
                }
            ],
            'end_date' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    if ($value && strtotime(now() > strtotime($value))) {
                        $fail(__("The :attribute must be a date greater than the current date.", ['attribute' => $attribute]));
                    }
                }
            ]
        ]);

        $this->checkMethod('post');
        $this->checkContentType($this->getPostHeader());

        DB::transaction(function () use ($request, $user, $userScope) {

            foreach ($request->scopes as $id) {
                $userScope = $userScope->fill($request->only('end_date'));
                $userScope->user_id = $user->id;
                $userScope->scope_id = $id;
                $userScope->createdBy();
                $userScope->save();
            }
        });

        return $this->message(__("Scopes have been assigned successfully"), 201);
    }

    /**
     * Revoke scope
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User\User $user
     * @param \App\Models\User\UserScope $userScope
     * @param \App\Models\Subscription\Scope $scope
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function revoke(Request $request, User $user, UserScope $userScope)
    {
        $this->validate($request, [
            'scopes' => [
                'required',
                function ($attribute, $value, $fail) use ($userScope) {
                    if (!is_array($value)) {
                        $fail(__("The :attribute must be an array", ['attribute' => $attribute]));
                    }

                    if (is_array($value)) {
                        foreach ($value as $id) {
                            try {
                                $scope = $userScope->find($id);
                                if (!$scope) {
                                    $fail(__("The :attribute is not valid", ["attribute" => $attribute, "id" => $id]));
                                }
                            } catch (QueryException $th) {
                                $fail(__("The :attribute is not valid", ["attribute" => $attribute, "id" => $id]));
                            }
                        }
                    }
                }
            ],
        ]);

        $this->checkMethod('put');
        $this->checkContentType($this->getUpdateHeader());

        DB::transaction(function () use ($request, $user, $userScope) {
            foreach ($request->scopes as $id) {
                $scope = $userScope->find($id);
                $scope->end_date = now();
                $scope->updatedBy();
                $scope->push();
            }
        });

        return $this->message(__("Scopes have been revoked successfully"), 201);
    }

    /**
     * Return the all roles assigned for the user
     * @param \App\Models\User\User $user
     * @param \App\Models\User\Scope $scope
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function history(User $user, UserScope $userScope)
    {
        $this->checkMethod('get');

        $params = $this->filter_transform($userScope->transformer);

        $scopes = $userScope->query();
        $scopes = $scopes->where('user_id', $user->id);

        $this->search($scopes, $params);

        $scopes = $scopes->get();

        return $this->showAll($scopes, $userScope->transformer);
    }
}
