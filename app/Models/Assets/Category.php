<?php

namespace App\Models\Assets;
 
use App\Models\Booking\Rent; 
use App\Models\Master as master;
use App\Transformers\Asset\CategoryTransformer; 
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends master
{
    use HasFactory, SoftDeletes;

    public $table = "categories";

    public $view = "";

    public $transformer = CategoryTransformer::class;

    protected $fillable = [
        'name',
        'price',
        'capacity',
        'air_conditionar',
        'tv',
        'bathroom',
        'cold_water',
        'hot_water',
        'fan',
        'wifi',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function rooms()
    { 
        return $this->hasMany(Room::class)->withTrashed();
    }

    public function calendar()
    {
        return $this->hasMany(Calendar::class);
    }

    public function rents() {
        return $this->hasMany(Rent::class);
    }
}
