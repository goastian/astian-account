<?php
namespace App\Console\Commands\Settings;

use App\Models\User\Employee;
use App\Models\User\Role;
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
    protected $signature = 'settings:create_user';

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
        $email = $this->ask('Please enter the user\'s email:');
        $role = $this->choice('Please select the role:', ['admin', 'client'], 0);

        $this->createUser($email, $role);

        $this->info('User created successfully.');
        $this->info('Email: ' . $email);
        $this->info('Password: password');
        $this->info('Role: ' . $role);

        return Command::SUCCESS;
    }

    public function createUser($email, $role)
    {
        DB::table('users')->insert([
            'id' => $user = Str::uuid(),
            'name' => $role === 'admin' ? 'admin' : 'client',
            'last_name' => $role === 'admin' ? 'administrador' : 'usuario',
            'email' => $email,
            'password' => Hash::make('password'), // Default password
            'verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $roleModel = Role::where('name', $role)->first();
        if ($roleModel) {
            Employee::find($user)->roles()->syncWithoutDetaching($roleModel->id);
        }
    }
}
