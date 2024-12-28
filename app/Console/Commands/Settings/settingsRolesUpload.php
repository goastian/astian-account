<?php
namespace App\Console\Commands\Settings;

use App\Models\User\Role;
use App\Models\User\Group;
use App\Models\User\Scope;
use Illuminate\Support\Str;
use App\Models\User\Service;
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
     * Summary of handle
     * @return void
     */
    public function handle()
    {
        $this->info("Upload roles");
        $this->upload_roles();
        $this->upload_groups();
        $this->info("Uploaded successfully");
    }
    
    /**
     * Upload default roles
     * @return void
     */
    public function upload_roles()
    {
        $roles = Role::rolesByDefault();
        foreach ($roles as $key => $value) {
            Role::updateOrCreate(
                ['name' => $value->name],
                [
                    'name' => $value->name,
                    'slug' => Str::slug($value->name),
                    'description' => $value->description,
                    'system' => 1
                ]
            );
        }
    }

    /**
     * Upload groups
     *
     * @return void
     */
    public function upload_groups()
    {
        $groups = Group::groupByDefault();

        foreach ($groups as $key => $value) {

            //upload system groups
            $group = Group::updateOrcreate(
                ['name' => $value->name],
                [
                    'name' => $value->name,
                    'slug' => Str::slug($value->name),
                    'description' => $value->description,
                    'system' => 1
                ]
            );
            //checking if it has services
            if ($value->services) {

                foreach ($value->services as $key1 => $value1) {
                    //Uploading Services Available for this groups
                    $service = Service::updateOrCreate(
                        ['name' => $value1->name],
                        [
                            'name' => $value1->name,
                            'slug' => Str::slug($value1->name),
                            'description' => $value1->description,
                            'system' => 1,
                            'group_id' => $group->id
                        ]
                    );

                    //check for this services has actions
                    if (isset($value1->actions)) {
                        foreach ($value1->actions as $key1 => $value2) {
                            //searching for action in roles Model
                            $role = Role::where('name', $value2->name)->first();

                            //create default scopes for this service
                            Scope::updateOrCreate(
                                [
                                    'service_id' => $service->id,
                                    'role_id' => $role->id
                                ],
                                [
                                    'service_id' => $service->id,
                                    'role_id' => $role->id,
                                    'requires_payment' => $value2->requires_payment,
                                    'public' => $value2->public,
                                    'active' => $value2->active,
                                    'price' => 0,
                                ]
                            );
                        }
                    }
                }
            }
        }
    }
}
