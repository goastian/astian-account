<?php
namespace App\Console\Commands\Passport;

use Exception;
use Illuminate\Support\Facades\File;
use Laravel\Passport\ClientRepository;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Console\ClientCommand as Command;

class ClientCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passport:client
        {--personal : Create a personal access token client}
        {--password : Create a password grant client}
        {--client : Create a client credentials grant client}
        {--name= : The name of the client}
        {--provider= : The name of the user provider}
        {--redirect_uri= : The URI to redirect to after authorization }
        {--user_id= : The user ID the client should be assigned to }
        {--public : Create a public client (Auth code grant type only) }
        {--force : Force re-generation of client keys}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a client for issuing access tokens';


    protected function createPersonalClient(ClientRepository $clients)
    {
        $force = $this->option('force');

        $existingClientId = config('passport.personal_access_client.id');
        $existingClientSecret = config('passport.personal_access_client.secret');

        if ($existingClientId && $existingClientSecret && !$force) {
            $this->info('Personal access client already exists. Use --force to regenerate.');
            return;
        }

        $name = $this->option('name') ?: $this->ask(
            'What should we name the personal access client?',
            config('app.name') . ' Personal Access Client'
        );

        $client = $clients->createPersonalAccessClient(
            null,
            $name,
            'http://localhost'
        );

        settingAdd('passport.personal_access_client.id', $client->getKey());
        settingAdd('passport.personal_access_client.secret', $client->plainSecret);

        //$this->updatePassportEnv($client->getKey(), $client->plainSecret);

        $this->info('Personal access client created successfully.');
    }



    /**
     * Add personal access client keys
     * @param mixed $clientId
     * @param mixed $clientSecret
     * @throws \Exception
     * @return void
     */
    public function updatePassportEnv($clientId, $clientSecret)
    {
        $envPath = base_path('.env');

        if (!File::exists($envPath)) {
            throw new Exception('.env file not found!');
        }

        $envContent = File::get($envPath);

        $variables = [
            'PASSPORT_PERSONAL_ACCESS_CLIENT_ID' => $clientId,
            'PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET' => $clientSecret,
        ];

        foreach ($variables as $key => $value) {
            $pattern = "/^{$key}=.*$/m";
            $replacement = "{$key}=\"{$value}\"";

            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= "\n{$replacement}";
            }
        }

        File::put($envPath, $envContent);

        Artisan::call('config:clear');
    }

}
