<?php

namespace App\Http\Controllers\Booking;

use App\Models\Assets\Room;
use App\Models\Booking\Rent;
use App\Models\Assets\Calendar;
use App\Models\Booking\Booking;
use App\Models\Booking\Company;
use App\Exceptions\ReportMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Events\Booking\StoreBookingEvent;
use App\Events\Asset\Room\EnableRoomEvent;
use App\Events\Asset\Room\UpdateRoomEvent;
use App\Events\Booking\DeleteBookingEvent;
use App\Events\Booking\UpdateBookingEvent;
use App\Events\Asset\Room\DisableRoomEvent;
use App\Http\Requests\Booking\StoreRequest;
use App\Http\Requests\Booking\UpdateRequest;
use App\Events\Reservation\UpdateReservationEvent;
use App\Events\Reservation\DestroyReservationEvent;
use App\Http\Controllers\GlobalController as Controller;
use App\Events\Asset\Category\Calendar\UpdateCategoryCalendarEvent;

class BookingController extends Controller
{

    public function __construct(Booking $booking)
    {
        parent::__construct();
        $this->middleware('transform.request:' . $booking->transformer)->only('update','store');
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
    public function store(StoreRequest $request, Booking $booking, Rent $rent, Calendar $calendar, Room $room)
    {
        /**
         * para realiza la actualizacion se debe tener en cuenta esto
         * si todos el dia termina a las 12, entonces el dia emieza 24 horas antes
         * siempre que se contabil
         */
        DB::transaction(function () use ($booking, $request, $rent) {

            $company = $this->get_company($request);

            /**
             * Creamos el registro con datos principales
             */
            $booking = $booking->fill($request->only('check_out')); //fecha de salida
            $booking->company_id = isset($company) ? $company->id : null; //si tiene algun dato lo agregamos
            $booking->code = $this->generateUniqueCode();
            $booking->check_in = now();
            $booking->save(); //guardamos el registro

            /**
             * proceso para registrar las habitaciones que se ingresen
             */
            foreach ($request->only('rooms') as $keys => $values) {
                //registramos en rent cada habitacion ingresada
                foreach ($values as $key => $value) {
                    $rent = (new Rent)->fill($value);
                    $rent->room_id = $value['id'];
                    $rent->booking_id = $booking->id;
                    $rent->save();

                    /**
                     * trata de deshabilitar una habitacion ocupada
                     * en caso falle lanzara una excepcion
                     */
                    if ($rent->room->deleted_at) {
                        $message = "esta habitacion no esta disponible, ";
                        $message .= "puede que este ocupada o por algun motivo desabilitada por mantenimiento.";
                        $message .= "si cree que esto es un error contactese con el area TI";
                        throw new ReportMessage(__($message), 400);
                    }

                    $rent->room->deleted_at = now();
                    $rent->room->push();

                    /**
                     * actualizar cantidad de habitaciones disponibles por categorias en el
                     * calendario usnado fechas de ingreso y salida con el id de la categoria
                     */
                    $booking->get_calendar($rent->room->category_id, now(), $request->check_out)
                        ->each(function ($calendar) use ($booking) {
                            $booking->can_not_update_calendar($calendar);
                            $calendar->available = $calendar->available - 1;
                            $calendar->push();
                        });
                }
            }
        });

        UpdateCategoryCalendarEvent::dispatch($this->AuthKey());
        DisableRoomEvent::dispatch($this->AuthKey());
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

            //guardamos los datos del check_in y check_out actual
            $old_check_in = $booking->check_in;
            $old_check_out = $booking->check_out;

            /**
             * actualizamos el check_in solo cuando sea una reserva
             * y vengan con nuevos datos a traves del request
             */
            if ($booking->is_reservation() and $request->check_in and
                strtotime($booking->check_in) != strtotime($request->check_in)) {
                $this->can_update[] = true;
                $booking->check_in = $request->check_in;
            }

            if ($request->check_out and strtotime($booking->check_out) != strtotime($request->check_out)) {
                //actualizamos el check_out
                $this->can_update[] = true;
                $booking->check_out = $request->check_out;
            }

            if ($request->ruc and $request->ruc != $booking->company->ruc) {
                $booking->company_id = $this->get_company($request)->id;
            }

            /**
             * Si se realizo algun cambio se actualizara la disponibilidada
             * en el calendario
             */
            if (in_array(true, $this->can_update)) {

                /**
                 * buscamos todas las habitaciones alquiladas
                 */
                $rents = $booking->rents()->get();

                /**
                 * desde la fecha actual  hasta el check_out anterior
                 * le sumamos 1 en el calendario
                 */

                foreach ($rents as $key => $value) {
                    /**
                     * actualizamos el campo deleted_at por medio de la relacion room
                     * con la nueva fecha en caso ese se encuentre habilitado
                     * siempre y cuando este el tiempo actual en el rango del check_in y check_out
                     */

                    if ($this->verify_time_is_betweem($request->get_check_in(),
                        $request->get_check_out()) and !isset($value->room->deleted_at)) {
                        $value->room->deleted_at = now();
                        $value->room->push();
                    }

                    /**
                     * actualizar cantidad de habitaciones disponibles por categorias en el
                     * calendario usnado fechas de ingreso y salida con el id de la categoria
                     * a la categoria a establecer la cantidad debe ser la categoria asociada a cada habitacion
                     * y no a la asociada en booking, esto es para  que se lleve una correlacion con la disponibilidad
                     * ya que no no seimpre se asigna a la que pertenece
                     */

                    /**
                     * antes de actualizar vamos a sumar 1 desde la fecha actual hasta el check_out que tenia en bookin
                     * antes de eso verificamos que tenga una habitacion asociada si utilizamos la
                     * categoria en rent para descontar en el calendario
                     */
                    $category = $value->room ? $value->room->category_id : $value->category_id;

                    $booking->get_calendar($category, $old_check_in, $old_check_out)
                        ->each(function ($calendar) {
                            $calendar->available = $calendar->available + 1;
                            $calendar->push();
                        });

                    /**
                     * ahora le restamos desde la fecha actual hasta la nueva fecha establecida
                     */
                    $booking->get_calendar($category, $request->get_check_in(), $request->get_check_out())
                        ->each(function ($calendar) use ($booking) {
                            $booking->can_not_update_calendar($calendar);
                            $calendar->available = $calendar->available - 1;
                            $calendar->push();
                        });
                }
            }

            //actualizar si se realizaron cambios
            if (in_array(true, $this->can_update)) {
                $booking->push();
            }

        });

        if (in_array(true, $this->can_update)) {
            UpdateCategoryCalendarEvent::dispatch($this->AuthKey());
            UpdateRoomEvent::dispatch($this->AuthKey());
            UpdateBookingEvent::dispatch($this->AuthKey());
            UpdateReservationEvent::dispatch($this->AuthKey());
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
        DB::transaction(function () use ($booking) {

            $booking->rents()->get()->each(function ($rent) use ($booking) {
                //habilitar habitacion
                if ($rent->room_id) {
                    $rent->room->deleted_at = null;
                    $rent->room->push();
                }

                //verificamos que tenga una habitacion asignada si no se usara la categoria
                //en rent
                $category = $rent->room ? $rent->room->category_id : $rent->category_id;

                //devolverla disponibildad de la habitacion en el calendario
                $booking->get_calendar($category, $booking->check_in, $booking->check_out)
                    ->each(function ($calendar) {
                        $calendar->available += 1;
                        $calendar->push();
                    });
            });

            $booking->delete();
        });

        DestroyReservationEvent::dispatch($this->AuthKey());
        UpdateCategoryCalendarEvent::dispatch($this->AuthKey());
        UpdateRoomEvent::dispatch($this->AuthKey());
        DeleteBookingEvent::dispatch($this->AuthKey());

        return $this->showOne($booking, $booking->transformer);
    }

    /**
     * activa las habitaciones desabilitadas donde el check_out ha finalizado
     *
     */
    public function activate_rooms()
    {
        if (strtotime(date('H:i', strtotime(now()))) > strtotime('12:15')) {
            Booking::get()->each(function ($booking) {
                //verificamos que la hora actual sea mayor al checkout para activar la habitacion
                if (strtotime(now()) > strtotime($booking->check_out)) {
                    $booking->rents()->get()->each(function ($rents) use ($booking) {
                        $room = Room::onlyTrashed()->find($rents->room_id);
                        $room->deleted_at = null;
                        $room->push();
                    });

                    EnableRoomEvent::dispatch($this->AuthKey());
                }
            });
        }

        return $this->message("Tarea efecutada a las " . date('Y-m-d H:i:s', strtotime(now())));
    }

    /**
     * crea una entidad si esta no existe
     */
    public function get_company($request)
    {
        $company = null;

        if ($request->ruc) {
            try { //tratamos de crearla
                $company = (new Company())->fill($request->only('company', 'ruc'));
                $company->save();

            } catch (QueryException $e) {
                //si ya existe la buscamos con su ruc para obtener el ID
                $company = Company::where('ruc', $request->ruc)->first();
            }
        }

        return $company;
    }

}
