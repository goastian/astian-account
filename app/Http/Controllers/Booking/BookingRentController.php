<?php

namespace App\Http\Controllers\Booking;

use App\Models\Assets\Room;
use App\Models\Booking\Rent;
use App\Models\Booking\Booking;
use App\Exceptions\ReportMessage;
use Illuminate\Support\Facades\DB;
use App\Events\Asset\Room\EnableRoomEvent;
use App\Events\Asset\Room\UpdateRoomEvent;
use App\Events\Asset\Room\DisableRoomEvent;
use App\Http\Requests\BookingRent\ShowRequest;
use App\Http\Requests\BookingRent\StoreRequest;
use App\Http\Requests\BookingRent\UpdateRequest;
use App\Http\Requests\BookingRent\DestroyRequest;
use App\Events\Booking\Rooms\StoreBookingRoomEvent;
use App\Events\Booking\Rooms\UpdateBookingRoomEvent;
use App\Transformers\Booking\BookingRentTransformer;
use App\Events\Booking\Rooms\DestroyBookingRoomEvent;
use App\Http\Controllers\GlobalController as Controller;
use App\Events\Asset\Category\Calendar\UpdateCategoryCalendarEvent;

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
    public function store(StoreRequest $request, Booking $booking, Rent $rent)
    {
        DB::transaction(function () use ($request, $booking, $rent) {

            //obtenemos la habitacion
            $room = Room::withTrashed()->find($request->room_id);

            if ($this->verify_time_is_betweem($booking->check_in, $booking->check_out) && $room->deleted_at) {
                $message = "esta habitacion no esta disponible,
                puede que este ocupada o por algun motivo desabilitada por mantenimiento.
                si cree que esto es un error contactese con el area TI";
                throw new ReportMessage(__($message), 400);
            }

            //deshabilitar habitacion solo si esta en el rango del check_in
            if ($this->verify_time_is_betweem($booking->check_in, $booking->check_out)) {
                $room->deleted_at = now();
                $room->push();
            }

            //crear registro en rent
            $rent = $rent->fill($request->all());
            $rent->booking_id = $booking->id;
            $rent->save();

            /**
             * si aun no se le asigna una habitacion se usara la categoria de rent
             */
            $category_id = $room ? $room->category_id : $rent->category_id;

            $booking->get_calendar($category_id, now(), $booking->check_out)
                ->each(function ($calendar) use ($booking) {
                    //verificar disponiblidad
                    $booking->can_not_update_calendar($calendar);
                    //actualizar  available
                    $calendar->available -= 1;
                    $calendar->push();

                });
        });

        StoreBookingRoomEvent::dispatch($this->AuthKey());
        UpdateCategoryCalendarEvent::dispatch($this->AuthKey());
        DisableRoomEvent::dispatch($this->AuthKey());

        return $this->showOne($rent, BookingRentTransformer::class, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request, Booking $booking, Rent $rent)
    {
        return $this->showOne($rent, BookingRentTransformer::class);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Booking $booking, Rent $rent)
    {
        DB::transaction(function () use ($request, $booking, $rent) {

            $old_category = $rent->room_id ? $rent->room->category_id : $rent->category_id;
            $update_calendar = [];

            if ($this->is_diferent($rent->room_id, $request->room_id)) {
                $this->can_update[] = true;
                $update_calendar[] = true;
                $rent->room_id = $request->room_id;

            }

            if ($this->is_diferent($rent->category_id, $request->category_id)) {
                $this->can_update[] = true;
                $update_calendar[] = true;
                $rent->category_id = $request->category_id;

            }

            if ($this->is_diferent($rent->price, $request->price)) {
                $this->can_update[] = true;
                $rent->price = $request->price;
            }
            /**
             * se actualiza el calendario siempre que se cambie la categoria o la habitacion
             */
            if (in_array(true, $update_calendar)) {
                /**
                 * verificamos que se encuentre en el rango del check_in y
                 * check_out para poder desabilitarla
                 */
                if ($this->verify_time_is_betweem($booking->check_in, $booking->check_out)) {
                    $rent->room->deleted_at = null;
                    $rent->room->push();
                }

                //regresar el caledario la disponibilidad de la habitacion cambiada
                $booking->get_calendar($old_category, $booking->check_in, $booking->check_out)
                    ->each(function ($calendar) {
                        $calendar->available += 1;
                        $calendar->push();
                    });

                /**
                 * obtenemos la nueva habitacion que se ingrese
                 */
                $room = Room::withTrashed()->find($request->room_id);

                /**
                 * verificamos que la habitacion no se encuentre deshabilitada
                 * seimpre que el check_in y el check_out este en el rango actual
                 */
                if ($this->verify_time_is_betweem($booking->check_in, $booking->check_out) && $room->deleted_at) {
                    $message = "esta habitacion no esta disponible, ";
                    $message .= "puede que este ocupada o por algun motivo desabilitada por mantenimiento.";
                    $message .= "si cree que esto es un error contactese con el area TI";
                    throw new ReportMessage(__($message), 400);
                }

                /**
                 * deshabilitamos la habitacion siempre que se encuentre en el rango del
                 * check_in y check_out
                 */
                if ($this->verify_time_is_betweem($booking->check_in, $booking->check_out)) {
                    $room->deleted_at = now();
                    $room->push();
                }

                $category_id = $room ? $room->category_id : $rent->category_id;

                //actualizamos la disponiblidad del la categoria de la habitacion para que haya una correlatividad
                $booking->get_calendar($category_id, $booking->check_in, $booking->check_out)
                    ->each(function ($calendar) use ($booking) {
                        $booking->can_not_update_calendar($calendar);
                        $calendar->available -= 1;
                        $calendar->push();
                    });
            }

            //actualizamos
            if (in_array(true, $this->can_update)) {
                $rent->push();
            }
        });

        //emitimos eventos
        if (in_array(true, $this->can_update)) {
            UpdateCategoryCalendarEvent::dispatch($this->AuthKey());
            UpdateRoomEvent::dispatch($this->AuthKey());
            UpdateBookingRoomEvent::dispatch($this->AuthKey());
        }

        return $this->showOne($rent->find($rent->id), BookingRentTransformer::class, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRequest $request, Booking $booking, Rent $rent)
    {
        if (count($booking->rents()->get()) < 2) {
            $message = "No puedes eliminar todas las habitaciones de un registros: ";
            $message .= "en todo caso puedes optar por cancelar el registro";
            throw new ReportMessage($message, 400);
        }

        DB::transaction(function () use ($booking, $rent) {

            $category = $rent->room_id ? $rent->room->category_id : $rent->category_id;

            //actualizamos la disponiblidad de la habitacion en el calendario
            $booking->get_calendar($category, now(), $booking->check_out)
                ->each(function ($calendar) {
                    $calendar->available += 1;
                    $calendar->push();
                });

            //habilitar la habitacion
            if ($rent->room_id) {
                $rent->room->deleted_at = null;
                $rent->room->push();
            }

            //eliminamos la habitacion
            $rent->delete();

        });

        UpdateCategoryCalendarEvent::dispatch($this->AuthKey());
        EnableRoomEvent::dispatch($this->AuthKey());
        DestroyBookingRoomEvent::dispatch($this->AuthKey());

        return $this->showOne($rent, BookingRentTransformer::class);
    }
}
