<?php
namespace App\Http\Controllers\Web\Admin;

use Inertia\Inertia;
use App\Models\User\User;
use App\Models\Subscription\Plan;
use App\Models\Subscription\Role;
use App\Models\Subscription\Group;
use App\Models\Subscription\Service;
use App\Models\Broadcasting\Broadcast;
use App\Http\Controllers\WebController;

class DashboardController extends WebController
{

    public function dashboard()
    {
        $users_by_month = User::query()
            ->selectRaw("TO_CHAR(created_at, 'TMMonth YYYY') as month, COUNT(id) as total")
            ->groupByRaw("TO_CHAR(created_at, 'TMMonth YYYY')")
            ->orderByRaw("MIN(created_at)")
            ->get();

        $last_users = User::latest('created_at')->take(10)->get();

        $groups = Group::count();
        $roles = Role::count();
        $services = Service::count();
        $users = User::count();
        $channels = Broadcast::count();
        $plans = Plan::count();

        $data = [
            'users_by_month' => $users_by_month,
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
