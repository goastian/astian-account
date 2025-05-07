<?php
namespace App\Http\Controllers\Manager;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Subscription\Transaction;
use App\Http\Controllers\GlobalController;
use Elyerr\ApiResponse\Exceptions\ReportError;

class TransactionManagerController extends GlobalController
{


    public function __construct()
    {
        parent::__construct();

        $this->middleware('scope:administrator_transaction_full,administrator_transaction_view')->only('index');
        $this->middleware('scope:administrator_transaction_full,administrator_transaction_update')->only('activate');
    }

    /**
     * show the all transaction
     * @param \App\Models\Subscription\Transaction $transaction
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Transaction $transaction)
    {
        $data = $transaction->query();

        $params = $this->filter_transform($transaction->transformer);

        $data = $this->searchByBuilder($data, $params);
        $data = $this->orderByBuilder($data, $transaction->transformer);

        return $this->showAllByBuilder($data, $transaction->transformer);
    }

    /**
     * Activate the transaction
     * @param mixed $request
     * @param \App\Models\Subscription\Transaction $transaction
     * @return void
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

            Transaction::paymentSuccessfully($meta);
        });

        return $this->message("Transaction activated successfully");
    }

}
