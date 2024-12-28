<?php

namespace App\Console\Commands\User;

use App\Models\User\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeteleAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:delete-accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete user accounts (clients only)';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info("Delete user accounts (clients only)");
        Log::info("Delete user accounts (clients only)");
        $user = new User();
        $user->remove_accounts();
    }
}
