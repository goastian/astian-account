<?php
namespace App\Console\Commands\Settings;

use Artisan;
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
    protected $description = 'Upload channels';

    /**
     * Loading default settings for the system, for example default roles, services, 
     * list of countries and channel for broadcast system 
     * @return void
     */
    public function handle()
    {
        $this->info("Upload channels");
        Artisan::call('settings:roles-upload');
        Artisan::call('settings:countries-upload');
        Artisan::call('settings:channels-upload'); 
        $this->info("Uploaded successfully");
    }
}
