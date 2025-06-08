<?php
namespace App\Http\Controllers\Web\Admin\Manager;

use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\WebController;
use App\Models\Subscription\Transaction;
use App\Services\Payment\PaymentManager;
use Elyerr\ApiResponse\Exceptions\ReportError;

class TransactionManagerController extends WebController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator_transactions_full, administrator_transactions_view')->only('index');
        $this->middleware('userCanAny:administrator_transactions_full, administrator_transactions_update')->only('activate');
    }

    /**
     * index
     * @param \App\Models\Subscription\Transaction $transaction
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Transaction $transaction)
    {
        // Retrieve params of the request
        $params = $this->filter_transform($transaction->transformer);

        // Prepare query
        $data = $transaction->query();

        // Eager loading
        $data->with(['user', 'package', 'partner']);


        // Search 
        $data = $this->searchByBuilder($data, $params);

        // Order by
        $data = $this->orderByBuilder($data, $transaction->transformer);

        if (request()->wantsJson()) {
            return $this->showAllByBuilder($data, $transaction->transformer);
        }

        return Inertia::render("Admin/Transaction/Index", ["route" => route('admin.transactions.index')]);
    }

    /**
     * Activate the transaction
     * @param \App\Models\Subscription\Transaction $transaction
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function activate(Transaction $transaction)
    {
        if (
            !in_array($transaction->status, [
                config('billing.status.pending.name'),
                config('billing.status.failed.name')
            ])
        ) {
            throw new ReportError("This action is not allowed for the current transaction.", 403);
        }

        DB::transaction(function () use ($transaction) {

            $meta = $transaction->response;

            $paymentManager = new PaymentManager();
            $paymentManager->forceActivation($transaction->payment_method, $meta);

        });

        return $this->message("Transaction activated successfully");
    }

}
