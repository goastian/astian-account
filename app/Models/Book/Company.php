<?php

namespace App\Models\Book;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    public $table = "companies";

    public $view = "";

    protected $fillable = [
        'company',
        'ruc'
    ];
    
}
