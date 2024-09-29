<?php

namespace App\Console\Commands\User;

use App\Models\User\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RemoveUnverifiedAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:remove-unverified-account';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unverified user account (Clients only)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Delete unverified user account (Clients only)");
        Log::info("Delete unverified user account (Clients only)");
        $user = new Employee();
        $user->remove_clients_unverified();

    }
}
