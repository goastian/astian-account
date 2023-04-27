<?php

namespace App\Http\Controllers\Asset;

use App\Events\Asset\StoreCategoryCalendarEvent;
use App\Events\Asset\UpdateCategoryCalendarEvent;
use App\Http\Controllers\GlobalController as Controller;
use App\Http\Requests\CategoryCalendar\Update;
use App\Models\Assets\Calendar;
use App\Models\Assets\Category;
use App\Transformers\Asset\CalendarTransformer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryCalendarController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('transform.request:' . CalendarTransformer::class)->only('store', 'update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Calendar $calendar)
    {

        $calendars = $calendar->all();

        return $this->showAll($calendars, $calendar->transformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Category $category, Calendar $calendar)
    {
        //dd($calendar->get()->last());
        /*
        no hay fechas registradas
        obtener la fecha actaul
        si
        se le agrega una catidad de dias apartit de la fecha
        si no obtenemos la ultima fecha
        agregamos dias a partir de la fehca
         */
        DB::transaction(function () use ($request, $category, $calendar) {

            try {
                //si exite fechas se buscara la ultima
                $last_day = date("Y-m-d", strtotime($category->calendar()->get()->last()->day . " + 1 days"));

            } catch (Exception $e) {
                //si no existen fechas inciamos con la fecha actual
                $last_day = now();
            }

            //  throw new ReportNewError(__("Please enter a number of rooms available for sale"), 422);
            $this->validate($request, [
                'available' => ['required', 'integer', 'max:10'],
                'add_days' => ['required', 'integer', 'max:365'],
            ]);

            //obtenemos una lista de fechas a partir de la $last_day
            $days = $calendar->generateDaysCollection($last_day, $request->add_days);

            //ahora agregamos todas las fechas al calendadio segun su categoria
            for ($i = 0; $i < count($days); $i++) {

                $category->calendar()->create([
                    'day' => $days[$i],
                    'available' => $request->available,
                    'category_id' => $category->id,
                ]);
            }

        });

        StoreCategoryCalendarEvent::dispatch($this->AuthKey());

        return $this->message(['data' =>
            [
                'message' => __("El calendario ha sido extendido hasta el " . $category->calendar()->get()->last()->day),
            ]], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category, Calendar $calendar)
    {
        $day = $calendar->find($category->id);

        return $this->showOne($day);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, Category $category, Calendar $calendar)
    { 
        DB::transaction(function() use ($request, $calendar){
            
            $calendar->available = $request->available;
            $calendar->push();

        });

        UpdateCategoryCalendarEvent::dispatch($this->AuthKey());
        
        return $this->showOne($calendar);
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
