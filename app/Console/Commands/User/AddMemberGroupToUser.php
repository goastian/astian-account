<?php
namespace App\Console\Commands\User;

use App\Models\User\User;
use Illuminate\Console\Command;
use App\Models\Subscription\Group;
use Log;

class AddMemberGroupToUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:add-member-group';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Added group member group to the users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $group = Group::where('slug', 'member')->first();

        $users = User::all();

        foreach ($users as $user) {
            $user->groups()->syncWithoutDetaching($group->id);
            Log::info("Added group $group->slug to the user $user->name");
        }
    }
}
