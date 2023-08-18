<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\GlobalController as Controller;
use App\Models\Booking\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function __construct(){
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Payment $payment)
    {
        $params = $this->filter_transform($payment->transformer);

        $payments = $this->search($payment->table, $params);

        return $this->showAll($payments, $payment->transformer);
    }
}
