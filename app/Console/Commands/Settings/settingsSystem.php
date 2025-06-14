<?php
namespace App\Console\Commands\Settings;

use Artisan;
use App\Models\Setting\Setting;
use Illuminate\Console\Command;

class settingsSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:system-start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install essential data to start the server';

    /**
     * Loading default settings for the system, for example default roles, services, 
     * list of countries and channel for broadcast system 
     * @return void
     */
    public function handle()
    {
        $this->info("Install server");
        Artisan::call('settings:roles-upload');
        Artisan::call('settings:countries-upload');
        Artisan::call('settings:channels-upload');
        Artisan::call('passport:install');
        Setting::setDefaultKeys();
        $this->info("Server installed successfully");
    }
}
