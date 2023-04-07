<?php

namespace App\Models\Book;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    use HasFactory;

    public $table = "rents";

    public $view = "";

    protected $fillable = [
        'category_id',
        'room_id',
        'price'
    ];
    
}
