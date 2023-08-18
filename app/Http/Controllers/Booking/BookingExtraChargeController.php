<?php

namespace App\Http\Controllers\Booking;

use Illuminate\Http\Request;
use App\Models\Booking\Booking;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\GlobalController as Controller;
use App\Http\Requests\BookingExtraCharge\DestroyRequest;
use App\Http\Requests\BookingExtraCharge\StoreRequest;
use App\Models\Booking\ExtraCharge;
use App\Transformers\Booking\BookingExtraChargeTransformer;

class BookingExtraChargeController extends Controller
{

    public function __construct(){
        parent::__construct();
        $this->middleware('transform.request:' . BookingExtraChargeTransformer::class)->only('store', 'update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Booking $booking)
    {
        $charges = $booking->extra_chargeable()->get()->makeHidden(['extra_chargeable_id', 'extra_chargeable_type']);
         
        return $this->showAll($charges, BookingExtraChargeTransformer::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, Booking $booking)
    { 
        DB::transaction(function() use($request, $booking){
            $booking->extra_chargeable()->create([
                'charge' => $request->charge,
                'amount' => $request->amount,
                'price' => $request->price                
            ])->save();
        });

        return $this->message(__('cargo extra agregado'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRequest $request, Booking $booking, ExtraCharge $charge)
    {
        $charge->delete();

        return $this->message("Cargo anulado");
    }
}
