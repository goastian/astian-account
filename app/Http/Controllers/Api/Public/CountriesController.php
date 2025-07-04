<?php
namespace App\Http\Controllers\Api\Public;

use Illuminate\Http\Request;
use App\Models\Global\Country;
use App\Http\Controllers\Controller;

class CountriesController extends Controller
{
    /**
     * Gateway to get the all countries
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Global\Country $country
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request, Country $country)
    {
        $countries = $country->query();

        if ($request->name) {
            $countries = $countries->whereRaw("LOWER(name_en) LIKE ?", ['%' . strtolower($request->name) . '%']);
        }

        $countries = $this->orderByBuilder($countries);

        return $this->showAllByBuilder($countries, null, 200, false);
    }
}
