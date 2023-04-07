<?php

namespace App\Models\Book;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    public $table = "books";

    public $view = "";

    protected $fillable = [
        'check_out',
        'client_id',
        'company_id'
    ];
    
}
