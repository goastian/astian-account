<?php
namespace App\Models\Global;

use App\Models\Master;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Master
{
    use HasFactory;

    public $table = "countries";

    public $timestamps = false;

    protected $fillable = [
        "name_en",
        "name_es",
        "continent_en",
        "continent_es",
        "capital_en",
        "capital_es",
        "dial_code",
        "code_2",
        "code_3",
        "tld",
        "km2",
        "emoji",
    ];

    /**
     * default countries
     * @return mixed
     */
    public static function defaultCountries()
    {
        return json_decode(file_get_contents(base_path('database/extra/countries.json')));
    }
}
