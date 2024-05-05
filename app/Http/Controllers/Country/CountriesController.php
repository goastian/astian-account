<?php
namespace App\Http\Controllers\Country;

use App\Http\Controllers\Controller;
use App\Models\Country\Country;

final class CountriesController extends Controller
{

    public function index(Country $country)
    {
        $countries = $country->all();

        return $this->showAll($countries, null, 200, false);
    }

}
