<?php
namespace App\Http\Controllers\Web\Account;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use App\Repositories\PackageRepository;
use App\Repositories\TransactionRepository;
use App\Http\Requests\UserSubscription\RenewRequest;
use App\Http\Requests\UserSubscription\StoreRequest;

class UserSubscriptionController extends WebController
{

    /**
     * Transaction repository
     * @var 
     */
    public $transactionRepository;

    /**
     * Package repository
     * @var 
     */
    public $packageRepository;

    /**
     * Constructor
     * @param \App\Repositories\TransactionRepository $transactionRepository
     * @param \App\Repositories\PackageRepository $packageRepository
     */
    public function __construct(TransactionRepository $transactionRepository, PackageRepository $packageRepository)
    {
        parent::__construct();
        $this->transactionRepository = $transactionRepository;
        $this->packageRepository = $packageRepository;
    }

    /**
     * Show the package for the user
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request)
    {
        if (request()->wantsJson()) {
            return $this->packageRepository->searchForUser($request);
        }

        return Inertia::render("Account/Subscription/Index");
    }

    /**
     * Create new subscription for the user
     * @param \App\Http\Requests\UserSubscription\StoreRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function buy(StoreRequest $request)
    {
        return $this->transactionRepository->buy($request->toArray());
    }

    /**
     * Cancel transaction
     * @param string $transaction_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function cancel(string $transaction_id)
    {
        return $this->transactionRepository->cancel($transaction_id);
    }

    /**
     * Renew the package
     * @param \App\Http\Requests\UserSubscription\RenewRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function renew(RenewRequest $request)
    {
        return $this->transactionRepository->renewByUser($request);
    }

    /**
     * Show the transaction view
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function success(Request $request)
    {
        $data = $this->transactionRepository->retrieveTransactionForUser($request->code);

        return view('payment.success', ['transaction' => $data]);
    }
}
