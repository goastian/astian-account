<?php

namespace App\Models\Book;

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
