<?php

namespace App\Http\Controllers\Booking;

use App\Events\Booking\Payments\StoreBookingPaymentEvent;
use App\Events\Booking\Payments\UpdateBookingPaymentEvent;
use App\Http\Controllers\GlobalController as Controller;
use App\Http\Requests\Payment\StoreRequest;
use App\Http\Requests\Payment\UpdateRequest;
use App\Models\Account\Accounting;
use App\Models\Booking\Booking;
use App\Models\Booking\Payment;
use App\Transformers\Booking\BookingPaymentTransformer;
use Illuminate\Support\Facades\DB;

class BookingPaymentController extends Controller
{

    public function __construct(Payment $payment)
    {
        parent::__construct();
        $this->middleware('transform.request:' . BookingPaymentTransformer::class)->only('store', 'update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Booking $booking, Payment $payment)
    {
        $payments = $booking->payments()->get()->makeHidden(['paymentable_id', 'paymentable_type']);

        return $this->showAll($payments, BookingPaymentTransformer::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, Booking $booking)
    {

        $code = "B" . $this->generateUniqueCode();

        DB::transaction(function () use ($request, $booking, $code) {
            $booking->payments()->create([
                'price' => $request->price,
                'description' => $request->description,
                'code' => $code,
                'type' => $request->type,
                'method' => $request->method,
            ])->save();

            Accounting::create([
                'description' => $request->description,
                'price' => $request->price,
                'code' => $code,
                'type' => $request->type,
                'method' => $request->method,
            ])->save();

        });

        StoreBookingPaymentEvent::dispatch($this->AuthKey());

        return $request->type == 'in' ?
        $this->message(__("el pago ha sido realizado con el codigo " . $code)) :
        $this->message(__("el reembolso ha sido realizado con el codigo " . $code));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Booking $booking, Payment $payment)
    {
        DB::transaction(function () use ($request, $payment) {

            if ($this->is_diferent($payment->description, $request->description)) {
                $this->can_update[] = true;
                $payment->description = $request->description;
            }

            if ($this->is_diferent($payment->price, $request->price)) {
                $this->can_update[] = true;
                $payment->price = $request->price;
            }

            if ($this->is_diferent($payment->type, $request->type)) {
                $this->can_update[] = true;
                $payment->type = $request->type;
            }

            if ($this->is_diferent($payment->method, $request->method)) {
                $this->can_update[] = true;
                $payment->method = $request->method;
            }

            if (in_array(true, $this->can_update)) {
                $payment->push();

                $accounting = Accounting::where('code', $payment->code)->first();
                $accounting->description = $request->description;
                $accounting->price = $request->price;
                $accounting->type = $request->type;
                $accounting->method = $request->method;
                $accounting->push();
            }
        });

        if (in_array(true, $this->can_update)) {
            UpdateBookingPaymentEvent::dispatch($this->AuthKey());

            return $request->type == 'in' ?
            $this->message(__("los datos del pago han sido modificado con codigo " . $payment->code)) :
            $this->message(__("los datos del reembolso han sido modificados con codigo " . $payment->code));
        }
    }

}
