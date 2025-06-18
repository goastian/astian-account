<?php
namespace App\Services\Payment\Drivers;

use Illuminate\Support\Fluent;
use App\Models\Subscription\Transaction;
use App\Repositories\TransactionRepository;
use App\Services\Payment\Contracts\PaymentMethod;

class OfflineSubscription implements PaymentMethod
{
    /**
     * Provider name
     * @var string
     */
    protected $method = "offline";


    /**
     * Repository of the transaction
     */
    public $repository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->repository = $transactionRepository;
    }

    /**
     * Process data
     * @param array $data
     * @return  
     */
    public function buy(array $data)
    {
        $user = auth()->user();

        $session = new Fluent([
            'id' => $this->repository->generateSessionId(),
            'currency' => $data['price']['currency'],
            'amount_subtotal' => $data['price']['amount'],
            'amount_total' => $data['price']['amount'],
            'payment_intent' => $this->repository->generateIntent(),
            'metadata' => [
                'user_id' => $user->id,
                'transaction_code' => $data['transaction_code'],
            ],
            'url' => route('users.checkout.success') . "?code={$data['transaction_code']}",
        ]);

        return $session;
    }

    public function chargeRecurringPayment(array $package)
    {

    }


    /**
     * Abort operation
     * @param \App\Models\Subscription\Transaction $transaction
     * @return void
     */
    public function cancel(Transaction $transaction)
    {

    }


    public function forceActivation(array $response)
    {
        $response['payment_method'] = null;
        $response["status"] = config('billing.status.successful.name');
        $response["receipt_url"] = route('users.checkout.success') . "?code={$response['metadata']['transaction_code']}";

        $this->repository->paymentSuccessfully($response, 'succeed');
    }
}
