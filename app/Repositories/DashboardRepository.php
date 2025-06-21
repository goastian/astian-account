<?php

namespace App\Repositories;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Models\Subscription\Plan;
use App\Models\Subscription\Role;
use App\Models\Subscription\Group;
use App\Models\Subscription\Service;
use Elyerr\ApiResponse\Assets\Asset;
use App\Models\Broadcasting\Broadcast;
use App\Models\Subscription\Transaction;
use Elyerr\ApiResponse\Assets\JsonResponser;
use App\Transformers\Partner\DataTransformer;


class DashboardRepository
{
    use JsonResponser, Asset;


    public function admin(Request $request)
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

        return $this->data(["data" => $data]);
    }


    /**
     * Show data for the dashboard in the partner view
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function partner(Request $request)
    {
        //Retrieve the user partner code
        $partnerCode = auth()->user()->partner->code ?? null;

        //type of filter by day , month or year
        $type = $request->type ?? 'day';
        $time = searchByDate($type);

        //Generate a transaction query
        $data = Transaction::query();

        //Retrieve transactions by partner code
        $data->whereHas('partner', function ($query) use ($partnerCode) {
            $query->where('code', $partnerCode);
        });

        //Filter by only status is successfully
        $data->where('status', config('billing.status.successful.name'));

        //Apply filter between days
        if ($request->has('start') && $request->has('end')) {

            $request->merge([
                'start' => $request->start . ' 00:00:00',
                'end' => $request->end . ' 23:59:59',
            ]);

            $data->whereBetween('created_at', [$request->start, $request->end]);
        }

        //Make a query to filter data
        $data->selectRaw("
                TO_CHAR(created_at, '{$time}') as date,  
                COUNT(id) as total,
                currency,
                ROUND(SUM(total * (partner_commission_rate / 100))) as commission
            ")
            ->groupByRaw("TO_CHAR(created_at, '{$time}'), currency")
            ->orderByRaw("TO_CHAR(created_at, '{$time}')");

        //Get all data
        $data = $data->get();
        //Sum all transactions
        $total_sales = $data->sum(function ($item) {
            return $item->total++;
        });

        //Sum commission by currency
        $total_commissions = $data
            ->groupBy('currency')
            ->map(function ($items, $currency) {
                $sum = $items->sum('commission');
                return [
                    'currency' => $currency,
                    'total' => $this->formatMoney($sum),
                ];
            })
            ->values()
            ->toArray();

        //Make output data
        return [
            "data" => fractal($data, DataTransformer::class)->toArray()['data'],
            "total_sales" => $total_sales,
            "total_commission" => $total_commissions
        ];
    }

    /**
     * Show the data for dashboard on the user
     * @param \Illuminate\Http\Request $request
     * @return JsonResponser
     */
    public function home(Request $request)
    {
        $latest = Transaction::whereHas('package', function ($query) {
            $query->where('id', auth()->user()->id);
        });

        return $this->data([
            'data' => [
                'transactions' => $latest->latest()->take(10)->get(),
            ]
        ]);
    }
}