<?php
namespace App\Models\Payment\Contracts;

use App\Models\Subscription\Transaction;

interface PaymentMethod
{
    /**
     * Process transaction
     * @param array $data
     * @return void
     */
    public function process(array $data);

    /**
     * Cancel process
     * @param \App\Models\Subscription\Transaction $transaction
     * @return void
     */
    public function cancel(Transaction $transaction);

}
