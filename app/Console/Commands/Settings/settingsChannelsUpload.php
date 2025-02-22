<?php
namespace App\Console\Commands\Settings;

use Illuminate\Console\Command;
use Elyerr\ApiResponse\Assets\Asset;
use App\Models\Broadcasting\Broadcast;

class settingsChannelsUpload extends Command
{
    use Asset;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:channels-upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload channels';

    /**
     * Summary of handle
     * @return void
     */
    public function handle()
    {
        $this->info("Upload channels");
        $this->upload_groups();
        $this->info("Uploaded successfully");
    }

    /**
     * Upload default channels
     * @return void
     */
    public function upload_groups()
    {
        $broadcasts = Broadcast::channelsByDefault();

        foreach ($broadcasts as $broadcast) {
            Broadcast::updateOrcreate(
                ['name' => $broadcast->name],
                [
                    'name' => $broadcast->name,
                    'slug' => $this->slug($broadcast->name),
                    'description' => $broadcast->description,
                    'system' => true,
                ]
            )->save();
        }
    }
}
