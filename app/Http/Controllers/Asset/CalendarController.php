<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\GlobalController as Controller;
use App\Models\Assets\Calendar; 

class CalendarController extends Controller
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
    public function index(Calendar $calendar)
    {   
        $params = $this->filter_transform($calendar->transformer);

        $calendars = $this->search($calendar->view, $params);

        return $this->showAll($calendars, $calendar->transformer);

    }
}
