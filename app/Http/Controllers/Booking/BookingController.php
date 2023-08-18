<?php

namespace App\Http\Controllers\Booking;

use App\Events\Booking\DeleteBookingEvent;
use App\Events\Booking\StoreBookingEvent;
use App\Events\Booking\UpdateBookingEvent;
use App\Http\Controllers\GlobalController as Controller;
use App\Http\Requests\Booking\StoreRequest;
use App\Http\Requests\Booking\UpdateRequest;
use App\Models\Booking\Booking;
use App\Models\Booking\Company;
use App\Models\Booking\Rent;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{

    public function __construct(Booking $booking)
    {
        parent::__construct();
        $this->middleware('transform.request:' . $booking->transformer)->only('update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Booking $booking)
    {
        $params = $this->filter_transform($booking->transformer);

        $bookings = $this->search($booking->view, $params);

        return $this->showAll($bookings, $booking->transformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, Booking $booking, Company $company, Rent $rent)
    {
        DB::transaction(function () use ($booking, $request, $company, $rent) {
            //gestion de la empresa
            if ($request->ruc) {
                try {
                    $company = $company->fill($request->only('company', 'ruc'));
                    $company->save();

                } catch (QueryException $e) {

                    $company = $company->where('ruc', $request->ruc)->first();
                }
            }

            //registro de instancia del huesped
            $booking = $booking->fill($request->only('check_out'));
            $booking->company_id = $company->id;
            $booking->save();

            //registro de las habitaciones
            foreach ($request->only('rooms') as $keys => $values) {
                foreach ($values as $key => $value) {
                    $rent = $rent->fill($value);
                    $rent->room_id = $value['id'];
                    $rent->booking_id = $booking->id;
                    $rent->save();
                }
            }

        });

        StoreBookingEvent::dispatch($this->AuthKey());

        return $this->showOne($booking, $booking->transformer, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        return $this->showOne($booking, $booking->transformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Booking $booking)
    {
        DB::transaction(function () use ($request, $booking) {

            if ($booking->check_out != $request->check_out) {
                $this->can_update[] = true;
                $booking->check_out = $request->check_out;
            }
            
            if (in_array(true, $this->can_update)) {
                $booking->push();
            }

        });

        if (in_array(true, $this->can_update)) {
            UpdateBookingEvent::dispatch($this->AuthKey());
        }

        return $this->showOne($booking, $booking->transformer, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        DeleteBookingEvent::dispatch($this->AuthKey());

        return $this->showOne($booking, $booking->transformer);
    }
}
