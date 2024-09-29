<?php
namespace App\Console\Commands\Settings;

use App\Models\User\Role;
use Illuminate\Console\Command;

class settingsRolesUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:roles-upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'upload roles';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Upload roles");
        $this->upload_groups();
        $this->info("Uploaded succesfully");

    }

    public function upload_groups()
    {
        array_map(function ($role) {
            Role::updateOrcreate(
                ['name' => $role->name],
                [
                    'name' => $role->name,
                    'description' => $role->description,
                    'public' => $role->public,
                    'required_payment' => $role->required_payment,
                ])->save();
        }, Role::rolesByDefault());
    }
}
