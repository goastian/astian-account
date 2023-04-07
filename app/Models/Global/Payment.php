<?php

namespace App\Models\Global;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public $table = "";

    public $view = "";

    protected $fillable = [
        'amount',
        'description',
        'file'
    ];
    
}
