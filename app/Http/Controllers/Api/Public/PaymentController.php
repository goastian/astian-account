<?php
namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    /**
     * Get the billing period
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function billingPeriod()
    {
        return $this->data(['data' => billing_periods()]);
    }

    /**
     * show the currencies
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function currencies()
    {
        return $this->data(['data' => billing_currencies()]);
    }

    /**
     * show the status of payments
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function paymentStatus()
    {
        return $this->data(['data' => billing_statuses()]);
    }

    /**
     * methods
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function methods()
    {
        return $this->data(['data' => billing_methods()]);
    }
}
