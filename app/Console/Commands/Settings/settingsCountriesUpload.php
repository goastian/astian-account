<?php
namespace App\Console\Commands\Settings;

use App\Models\Country\Country;
use Illuminate\Console\Command;

class settingsCountriesUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:countries-upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'upload countries';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info("Upload countries");
        $this->upload_countries();
        $this->info("Uploaded succesfully");
    }

    /**
     * Upload default countries
     * @return void
     */
    public function upload_countries()
    {
        $countries = Country::defaultCountries();

        foreach ($countries as $country) {
            Country::updateOrcreate(
                ['name_en' => $country->name_en],
                [
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
                ]
            )->save();
        }
    }
}
