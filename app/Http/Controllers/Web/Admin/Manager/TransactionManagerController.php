<?php
namespace App\Http\Controllers\Web\Admin\Manager;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use App\Repositories\TransactionRepository;

class TransactionManagerController extends WebController
{
    /**
     * Repository
     * @var 
     */
    public $repository;

    /**
     * Constructor
     * @param \App\Repositories\TransactionRepository $transactionRepository
     */
    public function __construct(TransactionRepository $transactionRepository)
    {
        parent::__construct();
        $this->repository = $transactionRepository;
        $this->middleware('userCanAny:administrator_transactions_full, administrator_transactions_view')->only('index');
        $this->middleware('userCanAny:administrator_transactions_full, administrator_transactions_update')->only('activate');
    }

    /**
     * Show the resources
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser|\Inertia\Response
     */
    public function index(Request $request)
    {
        if (request()->wantsJson()) {
            return $this->repository->search($request);
        }

        return Inertia::render("Admin/Transaction/Index", ["route" => route('admin.transactions.index')]);
    }

    /**
     * Activate the transaction
     * @param \App\Models\Subscription\Transaction $transaction
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function activate(string $id)
    {
        return $this->repository->activate($id);
    }
}
