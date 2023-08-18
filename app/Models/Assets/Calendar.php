<?php

namespace App\Models\Assets;

use App\Models\Assets\Category;
use App\Transformers\Asset\CalendarTransformer;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Calendar extends Model
{
    use HasFactory;

    public $table = "calendars";

    public $view = "calendar";

    public $transformer = CalendarTransformer::class;

    public $timestamps = false;

    protected $fillable = [
        'day',
        'available',
        'category_id',
    ];

    /**
     * @param Integer $len
     * @param Array
     **/
    public function generateDaysCollection($date, $len)
    {
        $initial_day = $date;

        $days = [];

        for ($i = 0; $i < $len; $i++) {
            $days[$i] = date('Y-m-d', strtotime($initial_day . "+ $i days"));
        }

        return $days;
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
