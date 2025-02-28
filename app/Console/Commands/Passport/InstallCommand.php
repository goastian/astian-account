<?php
namespace App\Console\Commands\Passport;

use Laravel\Passport\Console\InstallCommand as Command;

class InstallCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passport:install
                            {--uuids : Use UUIDs for all client IDs}
                            {--force : Overwrite keys they already exist}
                            {--length=4096 : The length of the private key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the commands necessary to prepare Passport for use';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $provider = in_array('users', array_keys(config('auth.providers'))) ? 'users' : null;

        $this->call('passport:keys', ['--force' => $this->option('force'), '--length' => $this->option('length')]);

        if ($this->option('uuids')) {
            $this->configureUuids();
        }

        $options = ['--personal' => true, '--name' => config('app.name') . ' Personal Access Client'];

        if ($this->option('force')) {
            $options['--force'] = true;
        }

        $this->call('passport:client', $options);
    }

}
