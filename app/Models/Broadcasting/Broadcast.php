<?php

namespace App\Models\Broadcasting;

use App\Models\Master;
use App\Transformers\Broadcast\BroadcastTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Broadcast as Broadcasting;

class Broadcast extends Master
{
    use HasFactory;

    public $table = "broadcasts";

    public $transformer = BroadcastTransformer::class;

    protected $fillable = [
        'channel',
        'description',
    ];

    /**
     * registra todos los canales disponibles
     *
     * @return mixed
     */
    public static function register()
    {
        $channels = Broadcast::all();

        foreach ($channels as $broadcast) {

            Broadcasting::channel($broadcast->channel . ".{id}", function ($user, $id) {
                return (int) $user->id === (int) $id;
            });

            Broadcasting::channel($broadcast->channel, function ($user) {
                return (int) $user->id === (int) request()->user()->id;
            });

        };
    }

}
