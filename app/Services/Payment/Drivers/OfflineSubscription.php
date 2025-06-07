<?php
namespace App\Services\Payment\Drivers;

use Illuminate\Support\Fluent;
use App\Models\Subscription\Transaction;
use App\Services\Payment\Contracts\PaymentMethod;

class OfflineSubscription implements PaymentMethod
{

    protected $method = "offline";

    /**
     * Process data
     * @param array $data
     * @return  
     */
    public function buy(array $data)
    {
        $user = auth()->user();

        $session = new Fluent([
            'id' => Transaction::generateSessionId(),
            'currency' => $data['price']['currency'],
            'amount_subtotal' => $data['price']['amount'],
            'amount_total' => $data['price']['amount'],
            'payment_intent' => Transaction::generateIntent(),
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

        Transaction::paymentSuccessfully($response, 'succeed');
    }
}
