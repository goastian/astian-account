<?php

namespace App\Models\User;

use App\Models\Master;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Master
{
    use HasFactory;

    public $table = "";

    public $view = "";

    public $transformer = "";

    protected $fillable = [
        //
    ];

}
