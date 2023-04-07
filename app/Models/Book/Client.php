<?php

namespace App\Models\Book;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    public $table = "clients";

    public $view = "";

    protected $fillable = [
        'name',
        'last_name',
        'document',
        'number',
        'country',
        'city',
        'phone',
        'email'
    ];
    
}
