<?php

namespace App\Models\Assets;

use App\Assets\Timestamps;
use App\Models\Booking\Rent;
use App\Transformers\Asset\CategoryTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes, Timestamps;

    public $table = "categories";

    public $view = "";

    public $transformer = CategoryTransformer::class;

    protected $fillable = [
        'name',
        'price',
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
        return $this->hasMany(Room::class);
    }

    public function calendar()
    {
        return $this->hasMany(Calendar::class);
    }

    public function rents() {
        return $this->hasMany(Rent::class);
    }
}
