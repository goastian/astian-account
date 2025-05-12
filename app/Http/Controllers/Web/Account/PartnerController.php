<?php
namespace App\Http\Controllers\Web\Account;

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
     * Show dashboard
     * @return \Inertia\Response
     */
    public function dashboard(Request $request, Transaction $transaction)
    {
        $data = $transaction->query();

        $data = $transaction->whereHas(
            'partner',
            function ($query) {
                $query->where('code', auth()->user()->partner->code ?? null);
            }
        );

        $data->where('status', config('billing.status.successful.name'));

        if ($request->has('start') && $request->has('end')) {
            $data->whereBetween('created_at', [$request->start, $request->end]);
        }

        $values = fractal($data->get(), TransactionTransformer::class)->toArray()['data'];

        $totalCommission = 0;

        foreach ($values as &$item) {
            $commission = round($item['cents'] * ($item['partner_commission_rate'] / 100), 0);
            $totalCommission += $commission;
        }

        $meta = [
            'counter' => count($values),
            'values' => $values,
            'total' => $totalCommission
        ];

        if (request()->wantsJson()) {
            return $this->data($meta);
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
