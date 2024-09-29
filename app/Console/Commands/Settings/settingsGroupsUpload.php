<?php
namespace App\Console\Commands\Settings;

use App\Models\User\Group;
use Illuminate\Console\Command;

class settingsGroupsUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:groups-upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'upload groups';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Upload groups");
        $this->upload_groups();
        $this->info("Uploaded succesfully");

    }

    public function upload_groups()
    {
        array_map(function ($group) {
            Group::updateOrcreate(
                ['name' => $group->name],
                [
                    'name' => $group->name,
                    'description' => $group->description,
                ])->save();
        }, Group::groupByDefault());
    }
}
