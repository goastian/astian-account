<?php

namespace App\Http\Controllers\Booking;
 
use App\Models\User\Client;
use App\Models\Booking\Rent; 
use App\Models\Booking\Booking;
use App\Exceptions\ReportMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Http\Requests\BookingRent\ShowRequest;
use App\Http\Requests\BookingRentClient\StoreRequest;
use App\Http\Requests\BookingRentClient\DestroyRequest;
use App\Http\Controllers\GlobalController as Controller;
use App\Events\Booking\Client\StoreBookingRoomClientEvent;
use App\Transformers\Booking\BookingRentClientTransformer;
use App\Events\Booking\Client\DestroyBookingRoomClientEvent;
use Elyerr\ApiExtend\Exceptions\ReportError;

class BookingRentClientController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('transform.request:' . BookingRentClientTransformer::class)->only('store','update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ShowRequest $request, Booking $booking, Rent $rent)
    {
        $huespeds = $rent->huespeds()->get()->makeHidden('created_at');

        return $this->showAll($huespeds, BookingRentClientTransformer::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, Booking $booking, Rent $rent, Client $huesped)
    {
        
        DB::transaction(function () use ($request, $rent, $huesped) {
            try {
                $rent->huespeds()->create([
                    'name' => $request->name,
                    'last_name' => $request->last_name,
                    'document' => $request->document,
                    'number' => $request->number,
                    'city' => $request->city,
                    'country' => $request->country,
                    'email' => $request->email,
                    'phone' => $request->phone,
                ])->save();

            } catch (QueryException $e) {
                try {
                    $huesped = $huesped->where('number', $request->number)->first();
                    $rent->huespeds()->save($huesped);
                } catch (QueryException $e) {
                    throw new ReportError(__('Ocurrio un error, Este usuario ya ha sido registrado, si cree que es un error contacte con su soporte tecnico'), 422);
                }
            }
        });

        StoreBookingRoomClientEvent::dispatch($this->AuthKey());

        return $this->message(__('Usuario registrado exitosamente'), 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRequest $request, Booking $booking, Rent $rent, Client $huesped)
    {
        $rent->huespeds()->detach($huesped->id);

        DestroyBookingRoomClientEvent::dispatch($this->AuthKey());

        return $this->message('Usuario removido de la habitacion');
    }
}
