<?php

namespace App\Models\Broadcasting;

use App\Models\Master;
use App\Transformers\Broadcast\BroadcastTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\QueryException;

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
     * Retrieve default channels for the system
     * @return mixed
     */
    public static function channelsByDefault()
    {
        return json_decode(file_get_contents(base_path('database/extra/channels.json')));
    }
}
