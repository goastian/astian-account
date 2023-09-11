<?php

namespace App\Http\Controllers\Asset;

use Exception; 
use App\Models\Assets\Calendar;
use App\Models\Assets\Category;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CategoryCalendar\Update;
use App\Transformers\Asset\CalendarTransformer;
use App\Http\Requests\Category\Calendar\StoreRequest;
use App\Transformers\Asset\CategoryCalendarTransformer;
use App\Http\Controllers\GlobalController as Controller;
use App\Events\Asset\Category\Calendar\StoreCategoryCalendarEvent;
use App\Events\Asset\Category\Calendar\UpdateCategoryCalendarEvent;

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
    public function index(Category $category)
    {
        $calendars = $category->calendar()->get();

        return $this->showAll($calendars, CategoryCalendarTransformer::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, Category $category, Calendar $calendar)
    {
        DB::transaction(function () use ($request, $category, $calendar) {
            try {
                //si exite fechas se buscara la ultima
                $last_day = date("Y-m-d", strtotime($category->calendar()->get()->last()->day . " + 1 days"));

            } catch (Exception $e) {
                //si no existen fechas inciamos con la fecha actual
                $last_day = now();
            }

            //obtenemos una lista de fechas a partir de la $last_day
            $days = $calendar->generateDaysCollection($last_day, $request->add_days);

            //ahora agregamos todas las fechas al calendadio segun su categoria
            for ($i = 0; $i < count($days); $i++) {

                $category->calendar()->create([
                    'day' => $days[$i],
                    'available' => $request->available ? $request->available : count($category->rooms()->get()),
                    'category_id' => $category->id,
                ]);
            }

        });

        StoreCategoryCalendarEvent::dispatch($this->AuthKey());

        return $this->message( __("El calendario ha sido extendido hasta el " . $category->calendar()->get()->last()->day), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category, Calendar $calendar)
    {

        return $this->showOne($calendar, CategoryCalendarTransformer::class);
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

        $this->validate($request, [
            'available' => ['nullable', 'integer', 'max:10', 'min:0'],
        ]);

        DB::transaction(function () use ($request, $calendar) {

            if ($calendar->available != $request->available) {
                $this->can_update[] = true;
                $calendar->available = $request->available;
            }

            if (in_array(true, $this->can_update)) {
                $calendar->push();
            }

        });

        if (in_array(true, $this->can_update)) {
            UpdateCategoryCalendarEvent::dispatch($this->AuthKey());
        }

        return $this->showOne($calendar, CategoryCalendarTransformer::class);
    }
}
