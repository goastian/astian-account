<?php
namespace App\Console\Commands\Settings;

use App\Models\Broadcasting\Broadcast;
use Illuminate\Console\Command;

class settingsChannelsUpload extends Command
{
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
     * Execute the console command.
     *
     * @return int
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
        array_map(function ($channel) {
            Broadcast::updateOrcreate(
                ['channel' => $channel->channel],
                [
                    'channel' => $channel->channel,
                    'description' => $channel->description,
                    'system' => true,
                ])->save();
        }, Broadcast::channelsByDefault());
    }
}
