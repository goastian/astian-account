<?php
namespace App\Services\Payment\Contracts;

use App\Models\Subscription\Package;
use App\Models\Subscription\Transaction;

interface PaymentMethod
{
    /**
     * Process a one-time payment
     * 
     * @param array $data Payment data
     * @return mixed Result of payment process (e.g. session object, confirmation)
     */
    public function buy(array $data);

    /**
     * Charge recurring payment
     * @param array $package
     * @return void
     */
    public function chargeRecurringPayment(array $package);

    /**
     * Cancel a subscription or payment process
     * 
     * @param Transaction $transaction Transaction model instance
     * @return mixed Result of cancellation process
     */
    public function cancel(Transaction $transaction);

    /**
     * Summary of forceActivation
     * @param array $response
     * @return void
     */
    public function forceActivation(array $response);
}
