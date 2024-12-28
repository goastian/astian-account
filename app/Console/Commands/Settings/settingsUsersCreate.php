<?php
namespace App\Console\Commands\Settings;

use App\Models\User\User;
use App\Models\User\Role;
use App\Models\User\Scope;
use App\Models\User\Service;
use App\Models\User\UserSubscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

        //searching admin service group administrator
        $service = Service::where('slug', 'admin')->first();
        //searching roles 
        $role = Role::where('slug', 'full')->first();

        //use admin id and role id to find the scope_id in scope Model
        $scope = Scope::where(['role_id' => $role->id, 'service_id' => $service->id])->first();

        //create id
        $id = (string) Str::uuid();

        DB::transaction(function () use ($id, $scope) {
            //create user
            $this->createUser($id);

            //final step add this scope id to user crated 
            UserSubscription::updateOrCreate(
                [
                    'user_id' => $id,
                    'target_id' => $scope->id
                ],
                [
                    'user_id' => $id,
                    'target_type' => $scope->type,
                    'target_id' => $id,
                    'system' => true,
                    'status' => 'active'
                ]
            );
        });
        return Command::SUCCESS;
    }

    /**
     * Create new user 
     * @param mixed $id
     * @return void
     */
    public function createUser($id)
    {
        $name = 'user_' . Str::random(10);
        $lastName = 'last_' . Str::random(10);
        $email = Str::random(10) . "@elyerr.xyz";
        $password = Str::random(20);

        DB::table('users')->insert([
            'id' => $id,
            'name' => $name,
            'last_name' => $lastName,
            'email' => $email,
            'accept_terms' => true,
            'password' => Hash::make($password),
            'verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->info('User created successfully.');
        $this->info('Email: ' . $email);
        $this->info('Password: ' . $password);
    }
}
