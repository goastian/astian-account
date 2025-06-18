<?php
namespace App\Console\Commands\Settings;

use Illuminate\Console\Command;
use App\Models\Subscription\Role;
use App\Models\Subscription\Group;
use App\Models\Subscription\Scope;
use Elyerr\ApiResponse\Assets\Asset;
use App\Models\Subscription\Service;

class settingsRolesUpload extends Command
{
    use Asset;

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
    protected $description = 'Upload roles';

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
        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role->name],
                [
                    'name' => $role->name,
                    'slug' => $this->slug($role->name),
                    'description' => $role->description,
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

        foreach ($groups as $grp) {

            //upload system groups
            $group = Group::firstOrCreate(
                [
                    'slug' => $this->slug($grp->name)
                ],
                [
                    'name' => $grp->name,
                    'slug' => $this->slug($grp->name),
                    'description' => $grp->description,
                    'system' => 1
                ]
            );
            //checking if it has services
            if (isset($grp->services)) {
                foreach ($grp->services as $srv) {
                    try {
                        //Uploading Services Available for this groups
                        $service = Service::firstOrCreate(
                            [
                                'name' => $srv->name,
                                'group_id' => $group->id
                            ],
                            [
                                'name' => $srv->name,
                                'slug' => $this->slug($srv->name),
                                'description' => $srv->description,
                                'visibility' => $srv->visibility ?? 'private',
                                'system' => 1,
                                'group_id' => $group->id
                            ]
                        );

                        //check for this services has actions
                        if (isset($srv->actions)) {
                            foreach ($srv->actions as $action) {
                                //searching for action in roles Model
                                $role = Role::where('name', $action->name)->first();

                                //create default scopes for this service
                                Scope::firstOrCreate(
                                    [
                                        'service_id' => $service->id,
                                        'role_id' => $role->id
                                    ],
                                    [
                                        'service_id' => $service->id,
                                        'role_id' => $role->id,
                                        'api_key' => $action->api_key,
                                        'public' => $action->public,
                                        'active' => $action->active,
                                    ]
                                );
                            }
                        }
                    } catch (\Throwable $th) {
                    }
                }
            }
        }
    }
}
