<?php
namespace App\Http\Controllers\Web\Admin\Subscription;

use Illuminate\Http\Request;
use App\Models\Subscription\Scope;
use App\Http\Controllers\WebController;

class ScopeController extends WebController
{

    /**
     * constructor of class
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator_scope_full,administrator_scope_view')->only('index');
        $this->middleware('wants.json')->only('index');
    }

    /**
     * Show the all resources for scopes
     * @param \App\Models\Subscription\Scope $scope
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request, Scope $scope)
    {
        // Create query
        $data = $scope->query();

        // Apply eager loading and filter by active and public
        $data = $data->with([
            'role',
            'service',
            'service.group'
        ])->where('active', true)
            ->where('public', false);

        // search by role name or slug
        if ($request->has('role')) {
            $data->whereHas('role', function ($query) use ($request) {
                return $query
                    ->where('name', 'like', "%" . $request->role . "%")
                    ->orWhere('slug', 'like', "%" . $request->role . "%");
            });
        }

        // Search by service name or slug
        if ($request->has('service')) {
            $data->whereHas('service', function ($query) use ($request) {
                return $query
                    ->where('name', 'like', "%" . $request->service . "%")
                    ->orWhere('slug', 'like', "%" . $request->service . "%");
            });
        }

        return $this->showAllByBuilder($data, $scope->transformer);
    }
}
