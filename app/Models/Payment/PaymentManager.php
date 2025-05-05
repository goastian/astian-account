<?php
namespace App\Models\Payment;

use Exception;
use App\Models\Subscription\Transaction;
use App\Models\Payment\Drivers\StripeSubscription;

class PaymentManager
{
    protected array $drivers = [
        'stripe' => StripeSubscription::class,
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
        return $this->resolve($method)->process($data);
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

