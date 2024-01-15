<?php

namespace App\Models\Notify;

use App\Models\Master as Model;
use App\Transformers\Notification\NotificationTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory; 


class Notification extends Model
{ 
    public $table = "notifications";

    //public $view = "";

    public $transformer = NotificationTransformer::class;

    protected $fillable = [
        //
    ];
    
    
}
