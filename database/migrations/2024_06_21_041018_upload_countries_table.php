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
        $contries = json_decode(file_get_contents(base_path('database/extra/countries.json')));

        foreach ($contries as $key => $value) {
            Country::create([
                "name_en" => $value->name_en,
                "name_es" => $value->name_es,
                "continent_en" => $value->continent_en,
                "continent_es" => $value->continent_es,
                "capital_en" => $value->capital_en,
                "capital_es" => $value->capital_es,
                "dial_code" => $value->dial_code,
                "code_2" => $value->code_2,
                "code_3" => $value->code_3,
                "tld" => $value->tld,
                "km2" => $value->km2,
                "emoji" => $value->emoji
            ])->save();
        }
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
