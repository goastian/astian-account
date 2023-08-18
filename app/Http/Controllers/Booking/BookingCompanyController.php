<?php

namespace App\Http\Controllers\Booking;

use App\Events\Booking\company\StoreBookingCompanyEvent; 
use App\Http\Controllers\GlobalController as Controller;
use App\Models\Booking\Booking;
use App\Models\Booking\Company;
use App\Transformers\Company\CompanyTransformer;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TypeError;

class BookingCompanyController extends Controller
{

    public function __construct(Company $company)
    {
        parent::__construct();
        $this->middleware('transform.request:' . $company->transformer)->only('store','update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Booking $booking)
    {
        try {

            $company = $booking->company()->first();

            return $this->showOne($company, CompanyTransformer::class);

        } catch (TypeError $e) {
            return $this->message(__('No se encontraron resultados'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Booking $booking, Company $company)
    {
        $this->validate($request, [
            'company' => ['required'],
            'ruc' => ['required'],
        ]);

        DB::transaction(function () use ($request, $booking, $company) {
            try {
                $company = $company->fill($request->only('company', 'ruc'));
                $company->save();

            } catch (QueryException $e) {
                $company = $company->where('ruc', $request->ruc)->first();
            }

            $booking->company_id = $company->id;
            $booking->push();

        });

        StoreBookingCompanyEvent::dispatch($this->AuthKey());

        return $this->showOne($company,CompanyTransformer::class, 201);
    } 
}
