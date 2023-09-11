<?php

namespace App\Http\Controllers\Reservation;

use App\Models\User\Client;
use App\Models\Booking\Rent;
use Illuminate\Http\Request;
use App\Models\Booking\Booking;
use App\Models\Booking\Company;
use App\Exceptions\ReportMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Http\Requests\Reservation\StoreRequest;
use App\Events\Reservation\StoreReservationEvent;
use App\Http\Controllers\GlobalController as Controller;
use App\Events\Asset\Category\Calendar\UpdateCategoryCalendarEvent;

class ReservationController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, Booking $booking, Company $company, Client $client)
    {
        DB::transaction(function () use ($booking, $request, $company, $client) {

            /**
             * cear una empresa si esta no existe
             *
             */
            if ($request->ruc) {
                try { //tratamos de crearla
                    $company = $company->fill($request->only('company', 'ruc'));
                    $company->save();

                } catch (QueryException $e) {
                    //si ya existe la buscamos con su ruc para obtener el ID
                    $company = $company->where('ruc', $request->ruc)->first();
                }
            }

            //datos del cliente
            try { //creamos al cliente si no existe
                $client = $client->fill($request->only('name', 'last_name', 'email', 'city', 'country', 'telefono'));
                $client->save();
            } catch (QueryException $e) { //si existe le actualizamos los datos
                $client = $client->where('email', $request->email)->first();
                $client->name = $request->name;
                $client->last_name = $request->last_name;
                $client->country = $request->country;
                $client->city = $request->city;
                $client->email = $request->email;
                $client->phone = $request->phone;
                $client->push();
            }

            /**
             * datos de la reserva
             */
            $booking = $booking->fill($request->only('check_in', 'check_out')); //fecha de salida
            $booking->company_id = isset($company) ? $company->id : null; //si tiene algun dato lo agregamos
            $booking->client_id = $client->id;
            $booking->type = "reservation";
            $booking->code = $this->generateUniqueCode();
            $booking->save(); //guardamos el registro

            /**
             * proceso para registrar las habitaciones que se ingresen
             */
            foreach ($request->only('rooms') as $keys => $values) {
                //registramos en rent cada habitacion ingresada
                foreach ($values as $key => $value) {
                    $rent = (new Rent)->fill($value);
                    $rent->booking_id = $booking->id;
                    $rent->save();

                    /**
                     * actualizar cantidad de habitaciones disponibles por categorias en el
                     * calendario usnado fechas de ingreso y salida con el id de la categoria
                     */
                    $booking->get_calendar($value['category_id'], $request->check_in, $request->check_out)
                        ->each(function ($calendar) {
                            if ($calendar->available < 1) {
                                $message = "Por favor revise la disponiblidad, el dia $calendar->day no tiene";
                                $message .= " habitaciones disponibles";
                                throw new ReportMessage(__($message), 400);
                            }

                            $calendar->available = $calendar->available - 1;
                            $calendar->push();

                        });

                }
            }
        });

        UpdateCategoryCalendarEvent::dispatch($this->AuthKey());
        StoreReservationEvent::dispatch($this->AuthKey());

        return $this->showOne($booking, $booking->transformer, 201);

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
    public function destroy($id)
    {
        //
    }
}
