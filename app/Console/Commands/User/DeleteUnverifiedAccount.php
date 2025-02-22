<?php

namespace App\Console\Commands\User;

use App\Models\User\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeleteUnverifiedAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:delete-unverified-account';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unverified user account (Clients only)';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info("Delete unverified user account (Clients only)");
        Log::info("Delete unverified user account (Clients only)");
        $user = new User();
        $user->remove_clients_unverified();
    }
}
