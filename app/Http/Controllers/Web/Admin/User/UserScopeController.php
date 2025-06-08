<?php
namespace App\Http\Controllers\Web\Admin\User;

use App\Traits\Scopes;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Models\User\UserScope;
use App\Models\Subscription\Scope;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\WebController;
use Illuminate\Database\QueryException;
use Elyerr\ApiResponse\Exceptions\ReportError;

class UserScopeController extends WebController
{
    use Scopes;
    /**
     * Construct of class
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator_user_full,administrator_user_view')->only('index');
        $this->middleware('userCanAny:administrator_user_full,administrator_user_assign')->only('assign');
        $this->middleware('userCanAny:administrator_user_full,administrator_user_revoke')->only('revoke');
        $this->middleware('userCanAny:administrator_user_full,administrator_user_history')->only('history');
    }

    /**
     * Show the all resources
     * @param \App\Models\User\User $user
     * @param \App\Models\User\UserScope $userScope
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(User $user, UserScope $userScope, Scope $scope)
    {
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

        return $this->showAllByBuilder($scopes, $userScope->transformer);
    }

    /**
     *  Create new scope
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User\User $user
     * @param \App\Models\User\UserScope $userScope
     * @param \App\Models\Subscription\Scope $scope
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assign(Request $request, User $user, UserScope $userScope, Scope $scope)
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

        DB::transaction(function () use ($request, $user, $userScope) {
            foreach ($request->scopes as $id) {

                //add scopes
                $us = $userScope->whereNull('package_id')
                    ->where(function ($query) {
                        $query->whereNull('end_date')
                            ->orWhere('end_date', '>', now());
                    })
                    ->where('scope_id', $id)
                    ->where('user_id', $user->id)
                    ->updateOrCreate([
                        'scope_id' => $id,
                        'user_id' => $user->id,
                        'end_date' => $request->end_date
                    ]);

                //sync groups by scopes
                $user->groups()->sync($us->scope->service->group->id);
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
    public function revoke(Request $request, User $user, UserScope $scope)
    {
        if (($user->id == auth()->user()->id) || ($user->id != $scope->user_id)) {
            throw new ReportError(__('Invalid request'), 400);
        }

        DB::transaction(function () use ($scope) {
            if (empty($scope->end_date) || $scope->end_date >= now()) {
                $scope->end_date = now();
                $scope->push();
            }

        });

        return $this->message(__("Scopes have been revoked successfully"), 200);
    }

    /**
     * Return the all roles assigned for the user
     * @param \App\Models\User\User $user
     * @param \App\Models\User\UserScope $userScope
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function history(User $user, UserScope $userScope)
    {
        $scopes = $userScope->query();
        $scopes->where('user_id', $user->id);

        return $this->showAllByBuilder($scopes, $userScope->transformer);
    }
}
