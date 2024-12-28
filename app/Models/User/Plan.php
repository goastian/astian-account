<?php

namespace App\Models\User;

use App\Models\Master;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Master
{
    use HasFactory;

    public $table = "plans";

    public $view = "";

    public $transformer = "";

    protected $fillable = [

    ];

}
