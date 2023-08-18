<?php

namespace App\Http\Controllers\Booking;

use App\Events\Booking\Rooms\DestroyBookingRoomEvent;
use App\Events\Booking\Rooms\StoreBookingRoomEvent;
use App\Http\Controllers\GlobalController as Controller;
use App\Http\Requests\BookingRent\DestroyRequest;
use App\Http\Requests\BookingRent\ShowRequest;
use App\Http\Requests\BookingRent\StoreRequest;
use App\Http\Requests\BookingRent\UpdateRequest;
use App\Models\Booking\Booking;
use App\Models\Booking\Rent;
use App\Transformers\Booking\BookingRentTransformer;
use Illuminate\Support\Facades\DB;

class BookingRentController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('transform.request:' . BookingRentTransformer::class)->only('store', 'update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Booking $booking)
    {
        $bookings = $booking->rents()->get();

        return $this->showAll($bookings, BookingRentTransformer::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, Booking $booking, Rent $room)
    {
        DB::transaction(function () use ($request, $booking, $room) {
            $room = $room->fill($request->all());
            $room->booking_id = $booking->id;
            $room->save();
        });

        return $this->showOne($room, BookingRentTransformer::class, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request, Booking $booking, Rent $room)
    {
        return $this->showOne($room, BookingRentTransformer::class);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Booking $booking, Rent $room)
    {
        DB::transaction(function () use ($request, $booking, $room) {
            if ($this->is_diferent($room->room_id, $request->room_id)) {
                $this->can_update[] = true;
                $room->room_id = $request->room_id;
            }

            if ($this->is_diferent($room->category_id, $request->category_id)) {
                $this->can_update[] = true;
                $room->category_id = $request->category_id;
            }

            if ($this->is_diferent($room->price, $request->price)) {
                $this->can_update[] = true;
                $room->price = $request->price;
            }

            if (in_array(true, $this->can_update)) {
                $room->push();
            }
        });

        if (in_array(true, $this->can_update)) {
            StoreBookingRoomEvent::dispatch($this->AuthKey());
        }

        return $this->showOne($room, BookingRentTransformer::class, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRequest $request, Booking $booking, Rent $room)
    {
        $room->delete();

        DestroyBookingRoomEvent::dispatch($this->AuthKey());

        return $this->showOne($room, BookingRentTransformer::class);
    }
}
