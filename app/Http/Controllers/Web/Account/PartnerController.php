<?php
namespace App\Http\Controllers\Web\Account;

use App\Transformers\Partner\DataTransformer;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\User\Partner;
use App\Http\Controllers\WebController;
use App\Models\Subscription\Transaction;
use App\Transformers\Partner\PartnerTransformer;
use App\Transformers\Partner\TransactionTransformer;

class PartnerController extends WebController
{

    public function __construct()
    {
        $this->middleware("userCanAny:reseller_partner_full");
    }

    /**
     * Summary of dashboard
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subscription\Transaction $transaction
     * @return \Inertia\Response | array
     */
    public function dashboard(Request $request, Transaction $transaction)
    {
        //Retrieve the user partner code
        $partnerCode = auth()->user()->partner->code ?? null;

        //type of filter by day , month or year
        $type = $request->type ?? 'day';
        $time = searchByDate($type);

        //Generate a transaction query
        $data = $transaction->newQuery();

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
        $meta = [
            "data" => fractal($data, DataTransformer::class)->toArray()['data'],
            "total_sales" => $total_sales,
            "total_commission" => $total_commissions
        ];

        if ($request->wantsJson()) {
            return $meta;
        }

        return Inertia::render("Partner/Index", [
            "sales" => $meta,
            "route" => route("partners.dashboard")
        ]);
    }


    /**
     * Show referral link
     * @return \Inertia\Response
     */
    public function show()
    {
        $partner = auth()->user()->partner;

        return Inertia::render("Partner/Refer", [
            "partner" => isset($partner) ? $partner->meta() : [],
            "route" => route('partners.generate'),
        ]);
    }

    /**
     * Generate new code
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function generate()
    {
        $partner = auth()->user()->partner;

        if (is_null($partner)) {
            $partner = Partner::create([
                'code' => Partner::generateReferralCode(),
                'user_id' => auth()->user()->id
            ]);
        }

        $partner["links"] = $partner->referLinks();

        return $this->showOne($partner, PartnerTransformer::class, 201);
    }


    /**
     * Sales
     * @param \App\Models\Subscription\Transaction $transaction
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function sales(Transaction $transaction)
    {
        $params = $this->filter_transform($transaction->transformer);

        $data = $transaction->query();

        $data = $transaction->whereHas(
            'partner',
            function ($query) {
                $query->where('code', auth()->user()->partner->code ?? null);
            }
        );

        $data = $this->searchByBuilder($data, $params);
        $data = $this->orderByBuilder($data, $transaction->transformer);

        if (request()->wantsJson()) {
            return $this->showAllByBuilder($data, TransactionTransformer::class);
        }

        return Inertia::render("Partner/Sales", [
            "sales" => $this->showAllByBuilderArray($data, TransactionTransformer::class),
            "route" => route("partners.sales")
        ]);
    }
}
