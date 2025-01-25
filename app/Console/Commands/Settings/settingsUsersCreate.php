<?php
namespace App\Console\Commands\Settings;

use App\Models\User\User;
use App\Models\User\UserScope;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use App\Models\Subscription\Role;
use App\Models\Subscription\Scope;
use Illuminate\Support\Facades\DB;
use App\Models\Subscription\Service;
use Illuminate\Support\Facades\Hash;

class settingsUsersCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user dynamically with a selected role';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $role = $this->choice('Please select the role:', ['admin', 'client'], 0);

        // Searching for admin service
        $service = Service::where('slug', 'admin')->first();

        //Searching role admin
        $role = Role::where('slug', 'full')->first();

        // Use admin id and role id to find the scope_id in scope Model
        $scope = Scope::where(['role_id' => $role->id, 'service_id' => $service->id])->first();

        DB::transaction(function () use ($scope) {
            // Create user
            $this->createUser($scope);
        });

        return Command::SUCCESS;
    }


    public function createUser($scope = null)
    {
        $dev_mode = true;

        if (app()->environment('production')) {
            // Request data manually in production
            $name = $this->ask('Enter the user\'s first name');
            $lastName = $this->ask('Enter the user\'s last name');
            $email = $this->ask('Enter the user\'s email');
            $password = $this->secret('Enter the user\'s password');
            $dev_mode = false;
        } else {
            // Use Faker in development
            $name = fake()->name();
            $lastName = fake()->lastName();
            $email = fake()->unique()->safeEmail();
            $password = Str::random(20);
        }

        $user = User::create([
            'name' => $name,
            'last_name' => $lastName,
            'email' => $email,
            'accept_terms' => true,
            'password' => Hash::make($password),
            'verified_at' => now(),
            'deleted_at' => $dev_mode ? now() : null,
        ]);

        if ($scope) {
            UserScope::create([
                'scope_id' => $scope->id,
                'user_id' => $user->id,
            ]);
        }


        if (!$dev_mode) {
            //send notification to the admins users to activate account
        }

        $this->info('User created successfully.');
        $this->info('Email: ' . $email);
        $this->info('Password: ' . $password);
    }
}
