<?php
namespace App\Models\Payment\Drivers;

use App\Models\Subscription\Transaction;
use App\Models\Payment\Contracts\PaymentMethod;
use Illuminate\Support\Fluent;

class P2pSubscription implements PaymentMethod
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
            'amount_subtotal' => $data['price']['cents'],
            'amount_total' => $data['price']['cents'],
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
