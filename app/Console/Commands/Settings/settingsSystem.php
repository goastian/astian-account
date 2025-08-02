<?php
namespace App\Console\Commands\Settings;

use Artisan; 
use Illuminate\Console\Command; 
use App\Services\Settings\Setting;
use Illuminate\Support\Facades\Log;

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
        Log::info("Install server");
        Artisan::call('settings:key-generator');
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('settings:roles-upload');
        Artisan::call('settings:countries-upload');
        Artisan::call('settings:channels-upload');
        Artisan::call('passport:keys');
        Setting::setDefaultKeys();
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        $this->info("Server installed successfully");
        Log::info("Server installed successfully");
    }
}
