<?php

namespace App\Models\Book;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $table = "categories";

    public $view = "";

    protected $fillable = [
        'category',
        'price',
        'air_conditionar',
        'tv',
        'bathroom',
        'cold_water',
        'hot_water',
        'fan',
        'wifi'
    ];
    
}
