<?php
namespace App\Models\Global;

use App\Models\Master as Model;
use App\Transformers\Notification\NotificationTransformer;

class Notification extends Model
{
    /**
     * Table name
     * @var string
     */
    public $table = "notifications";


    /**
     * Transformer class
     * @var 
     */
    public $transformer = NotificationTransformer::class;

    protected $fillable = [
        //
    ];
}
