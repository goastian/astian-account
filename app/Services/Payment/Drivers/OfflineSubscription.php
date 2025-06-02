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
    public function process(array $data)
    {
        $session_id = Transaction::generateSessionId();

        $session = new Fluent([
            'id' => $session_id,
            'currency' => $data['price']['currency'],
            'amount_subtotal' => $data['price']['amount'],
            'amount_total' => $data['price']['amount'],
            'payment_intent' => Transaction::generateIntent(),
            'url' => route('users.checkout.success') . "?session_id={$session_id}",
        ]);


        return $session;
    }


    /**
     * Abort operation
     * @param \App\Models\Subscription\Transaction $transaction
     * @return void
     */
    public function cancel(Transaction $transaction)
    {

    }
}
