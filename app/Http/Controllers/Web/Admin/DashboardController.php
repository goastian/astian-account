<?php
namespace App\Http\Controllers\Web\Admin;

use Inertia\Inertia;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Models\Subscription\Plan;
use App\Models\Subscription\Role;
use App\Models\Subscription\Group;
use App\Models\Subscription\Service;
use App\Models\Broadcasting\Broadcast;
use App\Http\Controllers\WebController;

class DashboardController extends WebController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator_admin_full,administrator_admin_dashboard');
    }

    public function dashboard(Request $request)
    {

        //type of filter by day , month or year
        $type = $request->type ?? 'day';
        $time = searchByDate($type);

        $users_by_month = User::query();

        //Apply filter between days
        if ($request->has('start') && $request->has('end')) {
            $request->merge([
                'start' => $request->start . ' 00:00:00',
                'end' => $request->end . ' 23:59:59',
            ]);

            $users_by_month->whereBetween('created_at', [$request->start, $request->end]);
        }

        $users_by_month = $users_by_month->selectRaw("TO_CHAR(created_at, '{$time}') as month, COUNT(id) as total")
            ->groupByRaw("TO_CHAR(created_at, '{$time}')")
            ->orderByRaw("TO_CHAR(created_at, '{$time}')")
            ->get();

        $last_users = User::latest('created_at')->take(10)->get();

        $groups = Group::count();
        $roles = Role::count();
        $services = Service::count();
        $users = User::count();
        $channels = Broadcast::count();
        $plans = Plan::count();

        $data = [
            'users_by_month' => $users_by_month->toArray(),
            'last_users' => $last_users,
            'groups' => $groups,
            'roles' => $roles,
            'services' => $services,
            'users' => $users,
            'channels' => $channels,
            'plans' => $plans,
        ];

        if (request()->wantsJson()) {
            return $this->data(["data" => $data]);
        }

        return Inertia::render("Admin/Dashboard/Index", [
            "data" => $data,
            "route" => route("admin.dashboard")
        ]);
    }
}
