<?php

namespace App\Console\Commands\User;

use App\Models\User\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RemoveAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:remove-accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete user accounts (clients only)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Delete user accounts (clients only)");
        Log::info("Delete user accounts (clients only)");
        $user = new Employee();
        $user->remove_accounts();
    }
}
