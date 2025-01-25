<?php

namespace App\Models\Broadcasting;

use App\Models\Master;
use App\Transformers\Broadcast\BroadcastTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Broadcast as Broadcasting;

class Broadcast extends Master
{
    use HasFactory;

    /**
     * Table name
     * @var string
     */
    public $table = "broadcasts";

    /**
     * Transformer class
     * @var 
     */
    public $transformer = BroadcastTransformer::class;

    /**
     * Fillable properties
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'slug',
        'system'
    ];

    /**
     * Cast properties
     * @var array
     */
    public $casts = [
        'system' => 'boolean',
    ];

    /**
     * Register the all channels available in the system
     * @return void
     */
    public static function register()
    {
        try {
            $channels = Broadcast::all();

            foreach ($channels as $broadcast) {

                /**
                 * Register private channels
                 */
                Broadcasting::channel($broadcast->channel . ".{id}", function ($user, $id) {
                    return (int) $user->id === (int) $id;
                });

                /**
                 * Register public channels
                 */
                Broadcasting::channel($broadcast->channel, function ($user) {
                    return (int) $user->id === (int) request()->user()->id;
                });

            }
            ;
        } catch (QueryException $e) {
        }
    }

    /**
     * Retrieve default channels for the system
     * @return mixed
     */
    public static function channelsByDefault()
    {
        return json_decode(file_get_contents(base_path('database/extra/channels.json')));
    }
}
