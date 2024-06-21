<?php

use App\Models\Country\Country;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        array_map(function ($country) {
            Country::create([
                "name_en" => $country->name_en,
                "name_es" => $country->name_es,
                "continent_en" => $country->continent_en,
                "continent_es" => $country->continent_es,
                "capital_en" => $country->capital_en,
                "capital_es" => $country->capital_es,
                "dial_code" => $country->dial_code,
                "code_2" => $country->code_2,
                "code_3" => $country->code_3,
                "tld" => $country->tld,
                "km2" => $country->km2,
                "emoji" => $country->emoji,
            ])->save();
        }, Country::defaultCountries());

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
