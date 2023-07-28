<?php

namespace App\Models\Assets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    public $table = "categories";

    public $view = "";

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
}
