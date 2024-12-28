<?php

namespace App\Models\User;

use App\Models\Master; 
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Master
{
    use HasFactory;

    public $table = "";

    public $view = "";

    public $transformer = "";

    protected $fillable = [
        //
    ];

}
