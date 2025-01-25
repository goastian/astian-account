<?php
namespace App\Http\Controllers\Global;

use App\Http\Controllers\Controller;
use App\Models\Country\Country;

final class CountriesController extends Controller
{
    public function index(Country $country)
    {
        $countries = $country->query();
        
        $countries = $country->orderBy('name_en', 'asc');

        $countries = $country->get();

        return $this->showAll($countries, null, 200, false);
    }
}
