<?php

namespace App\Models\User;

use App\Models\Master;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Master
{
    use HasFactory;

    public $table = "services";

    public $view = "";

    public $transformer = "";

    protected $fillable = [
        'name',
        'slug',
        'description',
        'group_id',
        'system'
    ];

}
