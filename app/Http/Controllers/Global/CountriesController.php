<?php
namespace App\Http\Controllers\Global;

use Illuminate\Http\Request;
use App\Models\Global\Country;
use App\Http\Controllers\Controller;

class CountriesController extends Controller
{
    public function index(Request $request, Country $country)
    {
        $countries = $country->query();

        if ($request->has('name_en')) {
            $countries = $countries->where('name_en', "LIKE", "%" . $request->name_en . "%");
        }

        $this->orderBy($countries);

        $countries = $countries->get();

        return $this->showAll($countries, null, 200, false);
    }
}
