<?php
namespace App\Services\Payment;

use Exception;
use App\Models\Subscription\Transaction;
use App\Services\Payment\Drivers\StripeSubscription;
use App\Services\Payment\Drivers\OfflineSubscription;

class PaymentManager
{
    protected array $drivers = [
        'stripe' => StripeSubscription::class,
        'offline' => OfflineSubscription::class,
    ];

    /**
     * Resolve instance
     * @param string $method
     * @throws \Exception
     * @return mixed|\Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application
     */
    public function resolve(string $method)
    {
        if (!isset($this->drivers[$method])) {
            throw new Exception("Unsupported method", 404);
        }

        return app($this->drivers[$method]);
    }

    /**
     * Process payment
     * @param string $method
     * @param array $data
     */
    public function buy(string $method, array $data)
    {
        return $this->resolve($method)->buy($data);
    }

    /**
     * Charge recurring payment
     * @param string $method
     * @param array $package
     */
    public function chargeRecurringPayment(string $method, $package)
    {
        return $this->resolve($method)->chargeRecurringPayment($package);
    }

    /**
     * Force activation of failed transactions
     * @param string $method
     * @param array $response
     */
    public function forceActivation(string $method, array $response)
    {
        return $this->resolve($method)->forceActivation($response);
    }

    /**
     * Cancel 
     * @param string $method
     * @param \App\Models\Subscription\Transaction $transaction
     */
    public function cancel(string $method, Transaction $transaction)
    {
        return $this->resolve($method)->cancel($transaction);
    }
}

