<?php

namespace App\Models\Assets;

use App\Transformers\Asset\CalendarTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    use HasFactory;

    public $table = "calendars";

    public $view = "";

    public $transformer = CalendarTransformer::class;

    protected $fillable = [
        //
    ];
    
}
